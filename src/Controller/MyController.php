<?php

namespace App\Controller;

use App\Repository\MerchRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function homepage(MerchRepository $merchRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['dob' => \DateTime::createFromFormat('d/m/Y', '8/5/2006')]);
        $alleMerch = $merchRepository->findAll();

        $myMerch = $alleMerch[array_rand($alleMerch)];

        return $this->render('my/homepage.html.twig', [
            'user'=> $user,
            'alleMerch' => $alleMerch,
            'myMerch' => $myMerch,
        ]);
    }
}
