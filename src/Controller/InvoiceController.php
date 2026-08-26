<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Generator\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class InvoiceController extends AbstractController
{
    #[Route('/dashboard/invoice/{id}/print', name: 'app_invoice_print')]
    public function printAction(Invoice $invoice, PdfGenerator $pdfGenerator): Response
    {
        $pdf = $pdfGenerator->getInvoice($invoice);

        if (empty($pdf)) {
            throw new NotFoundHttpException();
        }

        return new Response($pdf, 200, [
            'Content-type' => 'application/pdf',
            'Content-Disposition' => ' filename="' . $invoice->getDocumentNumber() . '.pdf"'
        ]);
    }
}
