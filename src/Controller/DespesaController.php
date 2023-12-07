<?php

namespace App\Controller;

use App\DTO\DespesaDTO;
use App\Entity\Despesa;
use App\Form\DespesaFormType;
use App\Services\DespesaService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DespesaController extends AbstractController
{
    private Serializer $serializer;

    public function __construct(
        private DespesaService $despesaService,
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    #[Route('/api/despesas', name: 'app_despesa')]
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        $despesas = $this->despesaService->getRepository()->findBy(['user' => $user], ['id' => 'DESC']);
        return $this->json($despesas);
    }

    #[Route('/api/criar-despesa', name: 'app_criar_despesa', methods: ['POST'])]
    public function criarDespesa(Request $request): JsonResponse
    {
        $despesaDTO = new DespesaDTO();
        $despesaForm = $this->createForm(DespesaFormType::class, $despesaDTO);
        $despesaForm->submit(json_decode($request->getContent(), true));

        if($despesaForm->isValid()){
            $despesaDTO->user = $this->getUser();
            $despesaDTO = $this->despesaService->save($despesaDTO);
            return $this->json($despesaDTO, 201);
        }

        $errosForm = $despesaForm->getErrors(true);

        if(count($errosForm) > 0){
            $erro = (string) $errosForm;
        } else {
            $erro = 'Nao foi possivel gravar a informacao';
        }

        return $this->json($erro);
    }

    #[Route('/api/despesa/{id}', name: 'app_find_despesa', methods: ['GET'])]
    public function findDespesa(Request $request, int $id): JsonResponse
    {
        $despesa = $this->despesaService->getRepository()->find($id);

        if(!$despesa instanceof Despesa){
            return $this->json('Despesa nao encontrada!', 404);
        }

        return $this->json($despesa);
    }

    #[Route('/api/despesa/delete/{id}', name: 'app_delete_despesa', methods: ['DELETE'])]
    public function deleteDespesa(Request $request, int $id)
    {
        $despesa = $this->despesaService->getRepository()->find($id);

        if(!$despesa instanceof Despesa){
            return $this->json([
                'success' => false,
                'message' => 'Despesa nao encontrada'
            ]);
        }

        try{
            $despesaDeleted = $this->despesaService->remove($despesa);
        }catch(Exception $e){
            return $this->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

        if($despesaDeleted){
            return $this->json([
                'success' => true,
                'message' => 'Despesa deletada com sucesso'
            ]);
        }
    }
}
