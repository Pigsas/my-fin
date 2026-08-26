<?php

namespace App\Controller;

use App\Settings\CompanySettings;
use Jbtronics\SettingsBundle\Form\SettingsFormFactoryInterface;
use Jbtronics\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SettingsController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly SettingsManagerInterface $settingsManager,
        private readonly SettingsFormFactoryInterface $settingsFormFactory
    ) { }

    #[Route('dashboard/settings', name: 'app_settings')]
    public function index(
        Request $request,
    ): Response
    {
        $builder = $this->settingsFormFactory->createMultiSettingsFormBuilder([
            CompanySettings::class,
        ]);
        $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->settingsManager->save();
            $this->addFlash('success', $this->translator->trans('app.ui.settings_was_saved'));
        }

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
