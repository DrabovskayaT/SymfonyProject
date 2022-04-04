<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\DependentFixtureInterface;
use App\Entity\Author;

class AuthorFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10000; $i++) {
            $author = new Author();
            $author->setName('author_' . $i);
            $this->addReference( 'nameAuthor_' . $i,  $author); 
            $manager->persist($author);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookFixtures::class,
        ];
    }
}
