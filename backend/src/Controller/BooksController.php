<?php

namespace App\Controller;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/books', name: 'api_books_')]
final class BooksController extends AbstractController
{
    #[Route('', name: 'findAll', methods: ['GET'])]
    public function index(BooksRepository $rep): JsonResponse
    {
        $books = $rep->findAll();
        return $this->json($books);
    }
    #[Route('/{id}', name: 'find', methods: ['GET'])]
    public function show(Books $book): JsonResponse
    {
        return $this->json($book);
    }
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] Books $book, EntityManagerInterface $em): JsonResponse
    {
        $em->persist($book);
        $em->flush();
        return $this->json($book, Response::HTTP_CREATED);
    }
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Books $book, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($book);
        $em->flush();
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(Books $book, EntityManagerInterface $em, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true); // avec true on au aura un tableau associatif sinon un objet
        if (isset($data['titre'])) {
            $book->setTitre($data['titre']);
        }
        if (isset($data['read'])) {
            $book->setRead($data['read']);
        }
        if (isset($data['rating'])) {
            $book->setRating($data['rating']);
        }
        $errors = $validator->validate($book);
        if (count($errors) > 0)
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);

        $em->flush();
        return $this->json($book);
    }
}
