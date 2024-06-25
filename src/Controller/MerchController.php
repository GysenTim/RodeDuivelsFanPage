<?php

namespace App\Controller;

use App\Repository\MerchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MerchController extends AbstractController
{
    #[Route('/merch/{id<\d+>}', name: 'app_merch_show')]
    public function show(int $id, MerchRepository $merchRepository): Response
    {
        $merch = $merchRepository->find($id);

        if (!$merch) {
            throw $this->createNotFoundException('Merch item not found');
        }

        return $this->render('merch/show.html.twig', [
            'merch' => $merch,
        ]
        );
    }
}
