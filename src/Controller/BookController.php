<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/book", requirements={"_locale": "en|ru"}, name="book_")
 */
class BookController extends AbstractController
{
    /**
     * @var BookRepository
     */
    protected $bookRepository;

     /**
     * @param BookRepository $bookRepository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }


    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function bookCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $bookName = $data['name'];
        $bookAuthor = $data['author'];

        if (empty($bookName) || empty($bookAuthor)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->bookRepository->createBook($bookName, $bookAuthor);

        return new JsonResponse(['status' => 'Book created!'], Response::HTTP_CREATED);
    }
}
