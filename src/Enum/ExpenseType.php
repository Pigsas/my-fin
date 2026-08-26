<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ExpenseType: string implements TranslatableInterface
{
    case GOODS_ACQUISITION = 'goods_acquisition';       // Prekių, medžiagų, žaliavų, detalių ir kt. įsigijimo
    case ASSET_OPERATION = 'asset_operation';            // Ilgalaikio turto eksploatavimo ir kitos išlaidos
    case TAXES_FEES = 'taxes_fees';                       // Mokesčiai, rinkliavos

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            ExpenseType::GOODS_ACQUISITION => $translator->trans('app.ui.goods_acquisition', locale: $locale),
            ExpenseType::ASSET_OPERATION => $translator->trans('app.ui.asset_operation', locale: $locale),
            ExpenseType::TAXES_FEES => $translator->trans('app.ui.taxes_fees', locale: $locale),
        };
    }
}
