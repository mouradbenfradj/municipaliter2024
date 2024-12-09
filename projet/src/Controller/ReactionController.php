<?php

namespace App\Controller;

use App\Entity\Reaction;
use App\Entity\Topic;
use App\Form\ReactionType;
use App\Repository\ReactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reaction')]
class ReactionController extends AbstractController
{
    #[Route('/', name: 'app_reaction_index', methods: ['GET'])]
    public function index(ReactionRepository $reactionRepository): Response
    {
        return $this->render('reaction/index.html.twig', [
            'reactions' => $reactionRepository->findAll(),
        ]);
    }

    #[Route('/topic/{id}/react', name: 'app_reaction_new_reaction')]
    public function newReaction(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        $reaction = new Reaction();
        $reaction->setTopic($topic);
        $reaction->setUser ($this->getUser ()); // Assurez-vous que l'utilisateur est connecté

        $form = $this->createForm(ReactionType::class, $reaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reaction->setCreatedAt(new \DateTime());
            $entityManager->persist($reaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_show', ['id' => $topic->getId()]);
        }

        return $this->render('reaction/new.html.twig', [
            'form' => $form->createView(),
            'topic' => $topic,
        ]);
    }

    #[Route('/new', name: 'app_reaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reaction = new Reaction();
        $form = $this->createForm(ReactionType::class, $reaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_reaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reaction/new.html.twig', [
            'reaction' => $reaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reaction_show', methods: ['GET'])]
    public function show(Reaction $reaction): Response
    {
        return $this->render('reaction/show.html.twig', [
            'reaction' => $reaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reaction $reaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReactionType::class, $reaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reaction/edit.html.twig', [
            'reaction' => $reaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reaction_delete', methods: ['POST'])]
    public function delete(Request $request, Reaction $reaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
