<?php

namespace App\Enums;

enum LanguageEnum: string
{
    case ENGLISH = 'en-US';
    case POLISH = 'pl-PL';
    case GERMAN = 'de-DE';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
