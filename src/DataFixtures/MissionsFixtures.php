<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MissionsFixtures extends Fixture
{
    protected $faker;
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        for($i = 1; $i <= 100; $i++)
        {
            $mission = new Mission();
            $mission->setDescription('testsetsteststsetsetsetstes');
            $mission->setJobName($this->faker->company);
            $mission->setStartAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeThisYear));
            $mission->addUser($this->getReference(User::class.'_'. $this->faker->numberBetween(1, 90)));
            $manager->persist($mission);
        }


        $manager->flush();
    }
}
