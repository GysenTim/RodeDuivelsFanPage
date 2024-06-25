<?php

namespace App\Controller;

use App\Repository\MerchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function homepage(MerchRepository $merchRepository): Response
    {
        $alleMerch = $merchRepository->findAll();

        $myMerch = $alleMerch[array_rand($alleMerch)];

        return $this->render('my/homepage.html.twig', [
            'alleMerch' => $alleMerch,
            'myMerch' => $myMerch,
        ]);
    }
}
