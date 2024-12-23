<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Entity\TacheVote;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use App\Repository\TacheVoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tache')]
class TacheController extends AbstractController
{
    #[Route('/', name: 'app_tache_index', methods: ['GET'])]
public function index(TacheRepository $tacheRepository, TacheVoteRepository $tacheVoteRepository): Response
{
    $taches = $tacheRepository->findAll();
    $userVotes = [];

    $user = $this->getUser();
    if ($user) {
        foreach ($taches as $tache) {
            $vote = $tacheVoteRepository->findOneBy(['tache' => $tache, 'user' => $user]);
            if ($vote) {
                $userVotes[$tache->getId()] = true;
            } else {
                $userVotes[$tache->getId()] = false;
            }
        }
    }

    return $this->render('tache/index.html.twig', [
        'taches' => $taches,
        'userVotes' => $userVotes,
    ]);
}


    #[Route('/nouveau', name: 'app_tache_nouveau', methods: ['GET'])]
    public function nouveau(TacheRepository $tacheRepository): Response
    {
        return $this->render('tache/nouveaux.html.twig', [
            'taches' => $tacheRepository->findByEtat('Nouveau'),
            'etat' => 0,
        ]);
    }
    #[Route('/employer', name: 'app_tache_employer', methods: ['GET'])]
    public function employer(TacheRepository $tacheRepository): Response
    {
        return $this->render('tache/employer.html.twig', [
            'taches' => $tacheRepository->findByEtat('Nouveau'),
            'etat' => 0,
        ]);
    }

    #[Route('/tache/rate', name: 'app_tache_rate', methods: ['POST'])]
    public function rate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->request->get('id');
        $rating = $request->request->get('rating');

        $tache = $entityManager->getRepository(Tache::class)->find($id);
        if ($tache) {
            $tache->setEval($rating);
            $entityManager->flush();

            return new Response('Evaluation mise à jour', Response::HTTP_OK);
        }

        return new Response('Tâche non trouvée', Response::HTTP_NOT_FOUND);
    }


    #[Route('/encour', name: 'app_tache_encour', methods: ['GET'])]
    public function encour(TacheRepository $tacheRepository): Response
    {
        return $this->render('tache/index.html.twig', [
            'taches' => $tacheRepository->findByEtat('En cours'),
            'etat' => 1,
        ]);
    }

    #[Route('/terminer', name: 'app_tache_terminer', methods: ['GET'])]
    public function terminer(TacheRepository $tacheRepository): Response
    {
        return $this->render('tache/index.html.twig', [
            'taches' => $tacheRepository->findByEtat('Terminé'),
            'etat' => 2,
        ]);
    }


    #[Route('/tache/vote', name: 'app_tache_vote', methods: ['POST'])]
public function vote(Request $request, EntityManagerInterface $entityManager, TacheVoteRepository $tacheVoteRepository): JsonResponse
{
    $id = $request->request->get('id');
    $rating = (int) $request->request->get('rating');
    $user = $this->getUser();

    $tache = $entityManager->getRepository(Tache::class)->find($id);

    if (!$tache) {
        return new JsonResponse(['status' => 'error', 'message' => 'Tâche non trouvée'], Response::HTTP_NOT_FOUND);
    }

    // Vérifier si l'utilisateur a déjà voté
    $existingVote = $tacheVoteRepository->findOneBy(['tache' => $tache, 'user' => $user]);

    if ($existingVote) {
        return new JsonResponse(['status' => 'error', 'message' => 'Vous avez déjà voté pour cette tâche'], Response::HTTP_FORBIDDEN);
    }

    // Enregistrer le vote de l'utilisateur
    $vote = new TacheVote();
    $vote->setTache($tache);
    $vote->setUser($user);
    $vote->setRating($rating);

    $entityManager->persist($vote);

    // Mettre à jour les votes dans l'entité Tache
    if ($rating === 1) {
        $tache->setVotesOneStar($tache->getVotesOneStar() + 1);
    } elseif ($rating === 2) {
        $tache->setVotesTwoStars($tache->getVotesTwoStars() + 1);
    } else {
        $tache->setVotesThreeStars($tache->getVotesThreeStars() + 1);
    }

    // Calculer l'évaluation finale
    $eval = $this->calculateEval($tache);
    $tache->setEval($eval);

    $entityManager->flush();

    return new JsonResponse(['status' => 'success', 'message' => 'Vote enregistré avec succès']);
}

    

    private function calculateEval(Tache $tache): string
    {
        $votesOneStar = $tache->getVotesOneStar();
        $votesTwoStars = $tache->getVotesTwoStars();
        $votesThreeStars = $tache->getVotesThreeStars();

        if ($votesOneStar >= $votesTwoStars && $votesOneStar >= $votesThreeStars) {
            return 'مراجعة';
        } elseif ($votesTwoStars > $votesOneStar && $votesTwoStars >= $votesThreeStars) {
            return 'الإنجاز تم بمستوى عالٍ من الجودة';
        } else {
            return 'جاهزة للتسليم';
        }
    }



    #[Route('/eval', name: 'app_tache_eval', methods: ['GET'])]
    public function eval(TacheRepository $tacheRepository): Response
    {
        return $this->render('tache/eval.html.twig', [
            'taches' => $tacheRepository->findByEtat('Terminé'),
            'etat' => 2,
        ]);
    }

    #[Route('/new', name: 'app_tache_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tache);
            $entityManager->flush();

            return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tache/new.html.twig', [
            'tache' => $tache,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tache_show', methods: ['GET'])]
    public function show(Tache $tache): Response
    {
        return $this->render('tache/show.html.twig', [
            'tache' => $tache,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tache_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tache/edit.html.twig', [
            'tache' => $tache,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tache->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tache);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
    }
}
