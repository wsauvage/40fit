<?php

namespace App\Controller;

use App\Repository\ChallengeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AppController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(Request $request, ChallengeRepository $challengeRepository): Response
    {
        $challenges = $challengeRepository->findAll();
        return $this->render('app/index.html.twig', [
            'challenges' => $challenges,
        ]);
    }


}
