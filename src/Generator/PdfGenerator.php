<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\Invoice;
use App\Enum\InvoiceType;
use Mpdf\Mpdf;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment;

readonly class PdfGenerator
{
    public function __construct(
        private Environment $twig,
        #[Autowire('%kernel.project_dir%/var/mpdf')]
        private string $tempDir,
    ) {
    }

    private function generatePdf(string $html, ?string $footer = null): Mpdf
    {
        $mpdf = new Mpdf([
            'tempDir' => $this->tempDir,
            'default_font' => 'FreeSans',
            'format' => [210, 297],
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 10,
            'margin_bottom'    => 13,
            'margin_footer' => 5
        ]);

        $mpdf->SetHTMLFooter('
				<div style="text-align:center; font-size:10pt;">Puslapis {PAGENO} iš {nbpg}</div>
			');

        $mpdf->WriteHTML("
				<html>
					<body>$html</body>
				</html>
			");
        if ($footer) {
            $mpdf->SetHTMLFooter($footer);
        }

        return $mpdf;
    }

    public function getInvoice(Invoice $invoice): ?string
    {
        $template = match ($invoice->getType()) {
            InvoiceType::STANDARD => 'pdf/invoice/standard.html.twig',
            InvoiceType::CREDIT => 'pdf/invoice/credit.html.twig',
            InvoiceType::PREPAYMENT => 'pdf/invoice/prepayment.html.twig',
            default => throw new \LogicException('Unexpected invoice type')
        };

        $html = $this->twig->render($template, [
            'invoice' => $invoice,
        ]);

        return $this->generatePdf($html)->Output($invoice->getDocumentNumber() . '.pdf', 'S');
    }
}
