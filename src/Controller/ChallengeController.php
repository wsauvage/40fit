<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Service\ChallengeHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/challenge', name: 'challenge_')]
final class ChallengeController extends AbstractController
{
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
