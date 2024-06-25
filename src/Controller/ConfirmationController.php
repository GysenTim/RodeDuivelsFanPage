<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfirmationController extends AbstractController
{
    #[Route(path: '/confirmation', name: 'app_confirmation')]
    public function confirm(): Response
    {
        return $this->render('confirmation/confirm.html.twig');
    }
}
