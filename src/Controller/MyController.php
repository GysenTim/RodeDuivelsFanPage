<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function homepage(UserRepository $userRepository, Request $request): Response
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
        ->add('userNR', IntegerType::class)
        ->add('dob', DateType::class)
        ->add('Submit', SubmitType::class)
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $check = $userRepository->findOneBy([
                'userNR' => $user->getUserNR(),
                'dob' => $user->getDob(),
            ]);
            if (null !== $check) {
                if (0 === $check->getMerchid()) {
                    return $this->redirectToRoute('app_merch_index', [
                        'id' => $check->getUserNR(),
                        'day' => $check->getDob()->format('d'),
                        'month' => $check->getDob()->format('m'),
                        'year' => $check->getDob()->format('Y'),
                    ]);
                }
            } else {
                dd($check);
            }
        }

        return $this->render('my/homepage.html.twig', [
            'form' => $form,
        ]);
    }
}
