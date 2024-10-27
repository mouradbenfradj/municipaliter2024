<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index(): Response
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    
    #[Route('/cours/document', name: 'app_cours_document')]
    public function document(ArticleRepository $articleRepository): Response
    {
        return $this->render('cours/document.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    
    #[Route('/cours/video', name: 'app_cours_video')]
    public function video(VideoRepository $videoRepository): Response
    {
        return $this->render('cours/video.html.twig', [
            'videos' => $videoRepository->findAll(),
        ]);
    }
}
