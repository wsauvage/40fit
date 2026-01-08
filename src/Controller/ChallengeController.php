<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Repository\ChallengeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/user/challenge', name: 'challenge_')]
final class ChallengeController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(Request $request, ChallengeRepository $challengeRepository): Response
    {
        $challenges = $challengeRepository->findAll();
        return $this->render('challenge/index.html.twig', [
            'challenges' => $challenges,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $challenge = $entityManager->getRepository(Challenge::class)->find($id);

        if (!$challenge) {
            throw $this->createNotFoundException("Challenge #{$id} introuvable");
        }

        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge,
        ]);
    }
}
