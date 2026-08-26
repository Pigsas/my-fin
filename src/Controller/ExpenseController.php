<?php

namespace App\Controller;

use App\Entity\Expense;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

final class ExpenseController extends AbstractController
{
    #[Route('dashboard/expenses/{id}/file', name: 'app_expense_get_file')]
    public function getFile(Expense $expense): Response
    {
        return $this->file($expense->getFile()->getPathname(), disposition: ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
