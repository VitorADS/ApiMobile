<?php

namespace App\Helpers;

class TipoDespesa
{
    const DEBITO = 0;
    const CREDITO = 1;

    const DEBITO_STR = 'DEBITO';
    const CREDITO_STR = 'CREDITO';

    public static function getOptions(): array
    {
        return [
            self::DEBITO => self::DEBITO_STR,
            self::CREDITO => self::CREDITO_STR
        ];
    }

    public static function getOptionByKey(int $key): string
    {
        $options = self::getOptions();
        return $options[$key];
    }
}