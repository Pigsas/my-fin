<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UnitType: string implements TranslatableInterface
{
    case HOUR = 'hour';
    case PCS = 'pcs';
    case KM = 'km';
    case M = 'm';
    case M2 = 'm2';
    case KG = 'kg';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            UnitType::HOUR => $translator->trans('app.ui.hour', locale: $locale),
            UnitType::PCS => $translator->trans('app.ui.pcs', locale: $locale),
            UnitType::KM => $translator->trans('app.ui.km', locale: $locale),
            UnitType::M => $translator->trans('app.ui.m', locale: $locale),
            UnitType::M2 => $translator->trans('app.ui.m2', locale: $locale),
            UnitType::KG => $translator->trans('app.ui.kg', locale: $locale),
        };
    }
}
