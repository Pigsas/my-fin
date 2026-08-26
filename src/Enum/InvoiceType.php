<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum InvoiceType: string implements TranslatableInterface
{
    case STANDARD = 'standard';
    case CREDIT = 'credit';
    case PREPAYMENT = 'prepayment';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            InvoiceType::STANDARD => $translator->trans('app.ui.invoice', locale: $locale),
            InvoiceType::CREDIT => $translator->trans('app.ui.refund', locale: $locale),
            InvoiceType::PREPAYMENT => $translator->trans('app.ui.pre_paid', locale: $locale),
        };
    }
}
