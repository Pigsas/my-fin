<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum InvoiceStatus: string implements TranslatableInterface
{
    case PAID = 'paid';
    case NOT_PAID = 'not_paid';
    case EXPIRED = 'expired';
    case DRAFT = 'draft';
    case CLOSED = 'closed';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            InvoiceStatus::PAID => $translator->trans('app.ui.paid', locale: $locale),
            InvoiceStatus::NOT_PAID => $translator->trans('app.ui.not_paid', locale: $locale),
            InvoiceStatus::EXPIRED => $translator->trans('app.ui.expired', locale: $locale),
            InvoiceStatus::DRAFT => $translator->trans('app.ui.draft', locale: $locale),
            InvoiceStatus::CLOSED => $translator->trans('app.ui.closed', locale: $locale),
        };
    }
}
