<?php

namespace App\Controller;

use App\Service\ChallengeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AppController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ChallengeHandler $challengeHandler): Response
    {
        $challenges = $challengeHandler->getChallenges();

        return $this->render('app/index.html.twig', [
            'challenges' => $challenges,
        ]);
    }


}
