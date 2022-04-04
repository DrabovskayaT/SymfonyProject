<?php

declare(strict_types=1);

namespace App\DataFixtures;

/**
 * DependentFixtureInterface needs to be implemented by fixtures which depend on other fixtures
 */
interface DependentFixtureInterface
{
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @psalm-return array<class-string<FixtureInterface>>
     */
    public function getDependencies();
}