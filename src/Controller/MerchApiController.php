<?php

namespace App\Controller;

use App\Repository\MerchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/merch')]
class MerchApiController extends AbstractController
{
    #[Route(path: '', methods: ['GET'])]
    public function getCollection(MerchRepository $merchRepository): Response
    {
        $merch = $merchRepository->findAll();

        return $this->json($merch);
    }

    #[Route(path: '/{id<\d+>}', methods: ['GET'])]
    public function get(int $id, MerchRepository $merchRepository): Response
    {
        $merchItem = $merchRepository->find($id);
        if (!$merchItem) {
            throw $this->createNotFoundException('Merch item not found');
        }

        return $this->json($merchItem);
    }
}
