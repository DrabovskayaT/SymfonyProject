<?php

namespace App\Controller;
use App\Repository\AuthorRepository;
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
class AuthorController extends AbstractController
{
     /**
     * @var AuthorRepository
     */
    protected $authorRepository;

     /**
     * @param AuthorRepository $authorRepository
     */
    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }


     /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function bookCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $authorName = $data['name'];
        
        if (empty($authorName)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->authorRepository->createBook($authorName);

        return new JsonResponse(['status' => 'Author created!'], Response::HTTP_CREATED);
    }
}
