<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ExpenseStatus: string implements TranslatableInterface
{
    case PAID = 'paid';
    case NOT_PAID = 'not_paid';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            ExpenseStatus::PAID => $translator->trans('app.ui.paid', locale: $locale),
            ExpenseStatus::NOT_PAID => $translator->trans('app.ui.not_paid', locale: $locale),
        };
    }
}
