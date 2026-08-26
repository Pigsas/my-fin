<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('dashboard/profile', name: 'app_profile')]
    public function index(
        Request $request,
    ): Response
    {

        return $this->render('profile/index.html.twig', [
        ]);
    }
}
