<?php

namespace App\Controller;

use App\Entity\Reunion;
use App\Form\ReunionType;
use App\Repository\ReunionRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reunion')]
class ReunionController extends AbstractController
{
    #[Route('/', name: 'app_reunion_index', methods: ['GET'])]
    public function index(ReunionRepository $reunionRepository): Response
    {
        return $this->render('reunion/index.html.twig', [
            'reunions' => $reunionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reunion_new')]
    public function new(Request $request, MailService $mailService)
    {
        $reunion = new Reunion();
        $form = $this->createForm(ReunionType::class, $reunion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reunion);
            $entityManager->flush();
            foreach ($reunion->getInvites() as $invite) {
                $emailContent = $this->renderView('emails/reunion_email.html.twig', ['sujet' => $reunion->getSujet(), 'description' => $reunion->getDescription(), 'date' => $reunion->getDate(), 'datefin' => $reunion->getDatefin()]);
                $mailService->sendEmail($invite->getEmail(), 'Invitation à la réunion', $emailContent);
            }
            return $this->redirectToRoute('reunion_list');
        }

        return $this->render('reunion/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reunion', name: 'reunion_list')]
    public function list()
    {
        $reunions = $this->getDoctrine()->getRepository(Reunion::class)->findAll();

        return $this->render('reunion/list.html.twig', [
            'reunions' => $reunions,
        ]);
    }

    #[Route('/{id}', name: 'app_reunion_show', methods: ['GET'])]
    public function show(Reunion $reunion): Response
    {
        return $this->render('reunion/show.html.twig', [
            'reunion' => $reunion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reunion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reunion/edit.html.twig', [
            'reunion' => $reunion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reunion_delete', methods: ['POST'])]
    public function delete(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reunion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reunion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
    }
}
