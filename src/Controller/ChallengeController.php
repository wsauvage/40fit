<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\Evaluation;
use App\Repository\ChallengeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/{id}/evaluations/create', name: 'evaluations_create')]
    public function evaluationCreate(Challenge $challenge, Request $request, EntityManagerInterface $entityManager): Response
    {
        $evaluation = (new Evaluation())
            ->setAssociatedUser($this->getUser())
            ->setChallenge($challenge);

        $evaluationForm = $this->createFormBuilder($evaluation)
            ->add('value', NumberType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        $evaluationForm->handleRequest($request);
        if ($evaluationForm->isSubmitted() && $evaluationForm->isValid()) {
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return $this->redirectToRoute('challenge_show', ['id' => $challenge->getId()]);
        }

        return $this->render('challenge/evaluation/create.html.twig', [
            'evaluationForm' => $evaluationForm,
        ]);
    }
}
