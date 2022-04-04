<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\DependentFixtureInterface;
use App\Entity\Book;

class BookFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10000; $i++) {
            $book = new Book();
            $book->setName('book-' . $i);
            $book->setAuthor($this->getReference('nameAuthor_' . $this->faker->numberBetween(0, 9)));
            $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [];
    }
}
