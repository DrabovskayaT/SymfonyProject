<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Book::class);
        $this->manager = $manager;
        $this->registry = $registry;
    }

    public function createBook($bookName, $bookAuthor)
    {
        $book = new Book();

        $author = $this->registry->getRepository(Author::class)
            ->findOneBy(['name' => $bookAuthor]);

        // если нет, создаём новый
        if (empty($author)) {
            $author = new Author();
            $author->setName($bookAuthor);
            $this->manager->persist($author);
            $this->manager->flush();
        }

        $book
            ->setName($bookName)
            ->setAuthor($author);

        $this->manager->persist($book);
        $this->manager->flush();
    }



    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Book $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    /**
     * @param Book $book
     * @return Book|JsonResponse
     */
    public function updateBook(Book $book)
    {
        $request = $this->requestStack->getCurrentRequest();
        $book = $book ? $book : new Book();
        //  $createBook = $createBook ? $createBook : new createBook();
        $book->name = $request->request->get('title');
        // $createBook->file = $request->files->get('file');
        $book->setAuthor($this->security->getAuthor());
        $book = $this->assignBook($book);
        //  $file = $book->getFile();
        //  $book->setFileSize($file->getSize());
        //  $info = exif_read_data($file);
        //  $book->setHeight($info['COMPUTED']['Height']);
        //  $book->setWidth($info['COMPUTED']['Width']);

        $constraints = $this->validator->validate($book);
        // $g = new gps();
        // $gps = $g->getGpsPosition($file);
        // if (empty($gps)) {
        //     return new JsonResponse(['errors' => "GPS data not found"], 400);
        // } else {
        //     $book->setLat($gps['latitude']);
        //     $book->setLng($gps['longitude']);
        // }
        if ($constraints->count()) {
            return new JsonResponse(['errors' => $this->handleError($constraints)], 400);
        }
        $book = $this->update($book); //update for getting filename
        //  $baseURI = $this->imagineCacheManager->generateUrl($book->getFilename(), 'my_thumb');
        //  $book->setFileUrl($baseURI);
        return $this->update($book);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Book $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
