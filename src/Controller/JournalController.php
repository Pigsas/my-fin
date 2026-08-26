<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Entity\Invoice;
use App\Enum\ExpenseType;
use App\Enum\InvoiceStatus;
use App\Enum\InvoiceType;
use App\Repository\ExpenseRepository;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JournalController extends AbstractController
{
    #[Route('dashboard/journal', name: 'app_journal')]
    public function index(
        Request $request,
        InvoiceRepository $invoiceRepository,
        ExpenseRepository $expenseRepository,
    ): Response
    {
        $year = $request->query->get('year', date('Y'));

        $invoices = $invoiceRepository->findByYear($year);
        $expenses = $expenseRepository->findByYear($year);

        $all = [];

        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            if ($invoice->getType() === InvoiceType::PREPAYMENT
            || $invoice->getStatus() !== InvoiceStatus::PAID) {
                continue;
            }

            $amount = $invoice->getTotal();

            $all[] = [
                'date' => $invoice->getDate()->format('Y-m-d'),
                'document_number' => $invoice->getDocumentNumber(),
                'amount' => $amount,
                'goods_acquisition' => '',
                'asset_operation' => '',
                'taxes_fees' => ''
            ];
        }

        /** @var Expense $expense */
        foreach ($expenses as $expense) {
            $all[] = [
                'date' => $expense->getDate()->format('Y-m-d'),
                'document_number' => $expense->getDocumentNumber(),
                'amount' => '',
                'goods_acquisition' => ($expense->getType() === ExpenseType::GOODS_ACQUISITION?$expense->getTotal():''),
                'asset_operation' => ($expense->getType() === ExpenseType::ASSET_OPERATION?$expense->getTotal():''),
                'taxes_fees' => ($expense->getType() === ExpenseType::TAXES_FEES?$expense->getTotal():''),
            ];
        }

        usort($all, fn($a, $b) => $a['date'] <=> $b['date']);

        return $this->render('journal/index.html.twig', [
            'year' => $year,
            'data' => $all,
        ]);
    }
}
