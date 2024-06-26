<?php

namespace App\Controller;

use App\Repository\ArtikelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MerchController extends AbstractController
{
    private ArtikelRepository $artikelRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    public function __construct(ArtikelRepository $artikelRepository, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->artikelRepository = $artikelRepository;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    #[Route('/merch/{id<\d+>}', name: 'app_merch_show')]
    public function show(int $id): Response
    {
        $merch = $this->artikelRepository->find($id);

        if (!$merch) {
            throw $this->createNotFoundException('Merch item not found');
        }

        return $this->render('merch/show.html.twig', [
            'merch' => $merch,
        ]
        );
    }

    #[Route('/merch/{id<\d+>}/{day<\d+>}/{month<\d+>}/{year<\d+>}', name: 'app_merch_index')]
    public function index(Request $request, int $id, int $day, int $month, int $year): Response
    {
        $merch = $this->artikelRepository->findAll();
        $dob = \DateTime::createFromFormat('d/m/Y', $day.'/'.$month.'/'.$year);
        $user = $this->userRepository->findOneBy([
            'userNR' => $id,
            'dob' => $dob,
        ]);
        if (!$user) {
            return $this->redirectToRoute('homepage');
        }
        if (0 !== $user->getMerchid()) {
            return $this->redirectToRoute('homepage');
        }
        $selectedMerchID = 0;
        $form = $this->createFormBuilder()
        ->add('merchOptions', ChoiceType::class, [
            'choices' => [
                't-shirt' => 1,
                'trui' => 2,
                'pet' => 3,
            ],
            'expanded' => true,
            'multiple' => false,
        ])
        ->add('Submit', SubmitType::class)
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedMerchID = $form->get('merchOptions')->getData();
            $selectedMerch = $this->artikelRepository->find($selectedMerchID);
            if (null !== $selectedMerch) {
                $user->setMerchid($selectedMerchID);
                $this->em->persist($user);
                $this->em->flush();

                return $this->redirectToRoute('app_confirmation');
            }
        }

        return $this->render('merch/index.html.twig', [
            'form' => $form,
            'merch' => $merch,
            'user' => $user,
        ]);
    }
}
