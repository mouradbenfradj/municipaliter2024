<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\EmployerVote;
use App\Entity\Feedback;
use App\Entity\Tache;
use App\Entity\TacheVote;
use App\Form\TacheType;
use App\Repository\EmployerVoteRepository;
use App\Repository\FeedbackRepository;
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
    public function index(TacheRepository $tacheRepository): Response
    {
        return $this->render('tache/index.html.twig', [
            'taches' => $tacheRepository->findAll(),
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

    #[Route('/eval_employer', name: 'app_tache_eval_employer', methods: ['GET'])]
    public function eval_employer(TacheRepository $tacheRepository): Response
    {
        return $this->render('tache/evalemployer.html.twig', [
            'taches' => $tacheRepository->findByEtat('Terminé'),
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
        return $this->render('tache/encour.html.twig', [
            'taches' => $tacheRepository->findByEtat('En cours'),
            'etat' => 1,
        ]);
    }
    #[Route('/tache/{id}/feedback', name: 'app_tache_feedback', methods: ['POST'])]
    public function feedback(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        $feedback = new Feedback();
        $feedback->setTache($tache);
        $feedback->setUtilisateur($this->getUser());
        $feedback->setContenu($request->request->get('contenu'));

        $entityManager->persist($feedback);
        $entityManager->flush();

        return $this->render('tache/feedbacks.html.twig', [
            'feedbacks' => [$feedback],
            'tache' => $tache, // Ajoutez la tâche au contexte pour l'utiliser dans le Twig
        ]);
    }
    #[Route('/tache/{id}/feedbacks', name: 'app_tache_feedbacks', methods: ['GET'])]
    public function feedbacks(Tache $tache, FeedbackRepository $feedbackRepository): Response
    {
        // Vérifie si la tâche existe
        if (!$tache) {
            throw $this->createNotFoundException('La tâche demandée n\'existe pas.');
        }

        // Récupère les feedbacks associés à la tâche
        $feedbacks = $feedbackRepository->findBy(['tache' => $tache]);

        // Si aucun feedback n'est trouvé, vous pouvez passer un message à la vue
        if (empty($feedbacks)) {
            $this->addFlash('info', 'Aucun feedback trouvé pour cette tâche.');
        }

        return $this->render('tache/feedbacks.html.twig', [
            'feedbacks' => $feedbacks,
            'tache' => $tache, // Ajoutez la tâche au contexte pour l'utiliser dans le Twig
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
    public function eval(TacheRepository $tacheRepository, TacheVoteRepository $tacheVoteRepository): Response
    {
        $taches = $tacheRepository->findByEtat('Terminé');
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

        return $this->render('tache/eval.html.twig', [
            'taches' => $taches,
            'userVotes' => $userVotes,
            'etat' => 2,
        ]);
    }

    #[Route('/worker/vote', name: 'app_worker_vote', methods: ['POST'])]
    public function voteWorker(Request $request, EntityManagerInterface $entityManager, EmployerVoteRepository $employerVoteRepository): JsonResponse
    {
        $workerId = $request->request->get('workerId');
        $rating = (int) $request->request->get('rating');
        $comment = $request->request->get('comment'); // Récupération du commentaire
        $user = $this->getUser();

        $worker = $entityManager->getRepository(Employer::class)->find($workerId);

        if (!$worker) {
            return new JsonResponse(['status' => 'error', 'message' => 'Worker non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Vérifiez si l'utilisateur a déjà voté
        $existingVote = $employerVoteRepository->findOneBy(['employer' => $worker, 'user' => $user]);

        if ($existingVote) {
            // Si l'utilisateur a déjà voté, mettez à jour la note et le commentaire
            $existingVote->setRating($rating);
            $existingVote->setComment($comment); // Mettez à jour le commentaire
        } else {
            // Sinon, créez un nouveau vote
            $vote = new EmployerVote();
            $vote->setEmployer($worker);
            $vote->setUser($user);
            $vote->setRating($rating);
            $vote->setComment($comment); // Assurez-vous de sauvegarder le commentaire

            $entityManager->persist($vote);
        }

        $entityManager->flush();

        $averageRating = $this->calculateAverageRating($worker);
        $worker->setEval($averageRating);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Évaluation du worker enregistrée avec succès']);
    }

    #[Route('/employer/vote', name: 'app_employer_vote', methods: ['POST'])]
    public function voteEmployer(Request $request, EntityManagerInterface $entityManager, EmployerVoteRepository $employerVoteRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $tacheId = $data['tacheId'];
        $ratings = $data['ratings']; // Array of employer ratings
        $user = $this->getUser();

        foreach ($ratings as $ratingData) {
            $employer = $entityManager->getRepository(Employer::class)->find($ratingData['employerId']);
            $rating = (int) $ratingData['rating'];
            $comment = $ratingData['comment']; // Retrieve the comment

            if ($employer) {
                $existingVote = $employerVoteRepository->findOneBy(['employer' => $employer, 'user' => $user]);

                if ($existingVote) {
                    $existingVote->setRating($rating);
                    $existingVote->setComment($comment); // Update the comment
                } else {
                    $vote = new EmployerVote();
                    $vote->setEmployer($employer);
                    $vote->setUser($user);
                    $vote->setRating($rating);
                    $vote->setComment($comment); // Save the comment

                    $entityManager->persist($vote);
                }

                $entityManager->flush();

                $averageRating = $this->calculateAverageRating($employer);
                $employer->setEval($averageRating);
                $entityManager->flush();
            }
        }

        return new JsonResponse(['status' => 'success', 'message' => 'Évaluations des employés enregistrées avec succès']);
    }

    private function calculateAverageRating(Employer $employer): string
    {
        $votes = $employer->getVotes();
        $totalVotes = count($votes);
        $sum = array_reduce($votes->toArray(), function ($carry, $vote) {
            return $carry + $vote->getRating();
        }, 0);

        return $totalVotes > 0 ? (string) ($sum / $totalVotes) : '0';
    }

    #[Route('/employer/comments/{employerId}', name: 'app_comments_page', methods: ['GET'])]
    public function commentsPage(int $employerId, EmployerVoteRepository $employerVoteRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les votes pour l'employé
        $votes = $employerVoteRepository->findBy(['employer' => $employerId]);

        // Récupérer l'employé
        $employer = $entityManager->getRepository(Employer::class)->find($employerId);

        // Mapper les commentaires
        $comments = array_map(function ($vote) {
            return [
                'user' => $vote->getUser()->getUsername(),
                'rating' => $vote->getRating(),
                'comment' => $vote->getComment(),
            ];
        }, $votes);

        return $this->render('comments/view.html.twig', [
            'comments' => $comments,
            'employer' => $employer, // Passer l'employé à la vue
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
