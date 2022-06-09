<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ExperiencesFixtures extends Fixture
{

    protected $faker;
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        for($i = 1; $i <= 100; $i++)
        {
            $exp = new Experience();
            $exp->setJobName($this->faker->title);
            $exp->setStartedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeThisYear));
            $exp->setDescription('teststsetsetestsetset');
            $exp->setUser($this->getReference(User::class.'_'. $this->faker->numberBetween(1, 90)));
            $manager->persist($exp);
        }


        $manager->flush();
    }
}
