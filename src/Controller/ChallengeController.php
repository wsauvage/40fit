<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\Evaluation;
use App\Form\ChallengeFilterType;
use App\Form\EvaluationType;
use App\Repository\ChallengeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/user/challenge", name: "challenge_")]
final class ChallengeController extends AbstractController
{
    #[Route("/", name: "index")]
    public function index(
        ChallengeRepository $challengeRepository,
        Request $request,
    ): Response {
        $challengeFilterForm = $this->createForm(ChallengeFilterType::class);

        $challengeFilterForm->handleRequest($request);
        $category = null;
        if (
            $challengeFilterForm->isSubmitted() &&
            $challengeFilterForm->isValid()
        ) {
            $category = $challengeFilterForm->get("category")->getData();
        }
        $challenges = $challengeRepository->findByCategory($category);

        return $this->render("challenge/index.html.twig", [
            "challengeFilterForm" => $challengeFilterForm,
            "challenges" => $challenges,
        ]);
    }

    #[Route("/{id}", name: "show")]
    public function show(
        int $id,
        EntityManagerInterface $entityManager,
    ): Response {
        $challenge = $entityManager->getRepository(Challenge::class)->find($id);
        if (!$challenge) {
            throw $this->createNotFoundException(
                "Challenge #{$id} introuvable",
            );
        }
        return $this->render("challenge/show.html.twig", [
            "challenge" => $challenge,
        ]);
    }

    #[Route("/{id}/evaluations/create", name: "evaluations_create")]
    public function evaluationCreate(
        Challenge $challenge,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $evaluation = new Evaluation()
            ->setAssociatedUser($this->getUser())
            ->setChallenge($challenge);

        $evaluationForm = $this->createForm(EvaluationType::class, $evaluation);

        $evaluationForm->handleRequest($request);

        if ($evaluationForm->isSubmitted()) {
            $errors = $evaluationForm->getErrors(true);
            if (count($errors) > 0) {
                $this->addFlash(
                    "error",
                    "Impossible de soumettre le formulaire",
                );
            }
        }

        if ($evaluationForm->isSubmitted() && $evaluationForm->isValid()) {
            $entityManager->persist($evaluation);
            $entityManager->flush();

            $this->addFlash("success", "Super une nouvelle évaluation !");

            return $this->redirectToRoute("challenge_show", [
                "id" => $challenge->getId(),
            ]);
        }

        return $this->render("challenge/evaluation/create.html.twig", [
            "evaluationForm" => $evaluationForm,
            "challenge" => $evaluation->getChallenge(),
        ]);
    }

    #[
        Route(
            "/{id}/evaluations/{evaluationId}/update",
            name: "evaluations_update",
        ),
    ]
    public function evaluationUpdate(
        Challenge $challenge,
        #[MapEntity(id: "evaluationId")] Evaluation $evaluation,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $evaluationForm = $this->createForm(EvaluationType::class, $evaluation);

        $evaluationForm->handleRequest($request);
        if ($evaluationForm->isSubmitted() && $evaluationForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute("challenge_show", [
                "id" => $challenge->getId(),
            ]);
        }

        return $this->render("challenge/evaluation/update.html.twig", [
            "evaluationForm" => $evaluationForm,
            "challenge" => $evaluation->getChallenge(),
        ]);
    }
}
