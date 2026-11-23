<?php

namespace App\Controller;

use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/books', name: 'api_books_')]
final class BooksController extends AbstractController
{
    #[Route('', name: 'findAll', methods: ['GET'])]
    public function index(BooksRepository $rep): JsonResponse
    {
        $books = $rep->findAll();
        return $this->json($books);
    }
}
