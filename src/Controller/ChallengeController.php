<?php

namespace App\Controller;

use App\Service\ChallengeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/challenge', name: 'challenge_')]
final class ChallengeController extends AbstractController
{
    #[Route('/{id}', name: 'show')]
    public function show(ChallengeHandler $challengeHandler, int $id): Response
    {

        $challenge = $challengeHandler->getChallengeById($id);

        if (!$challenge) {
            throw $this->createNotFoundException("Challenge #{$id} introuvable");
        }

        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge,
        ]);
    }
}
