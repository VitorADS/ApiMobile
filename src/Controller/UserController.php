<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserController extends AbstractController
{
    private Serializer $serializer;

    public function __construct(
        private UserService $userService,
        private UserPasswordHasherInterface $userPasswordHasher,
    )
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    #[Route('/api/register', name: 'app_register_user')]
    public function index(Request $request): JsonResponse
    {
        $user = new User();
        $userForm = $this->createForm(UserRegisterType::class, $user);
        $userForm->submit(json_decode($request->getContent(), true));

        if($userForm->isValid()){
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, $userForm->get('password')->getData())
            );
            $user = $this->userService->save($user);
            return $this->json($user, 201);
        }

        $errosForm = $userForm->getErrors(true);

        if(count($errosForm) > 0){
            $erro = (string) $errosForm;
        } else {
            $erro = 'Nao foi possivel gravar a informacao';
        }

        return $this->json([$erro]);
    }
}
