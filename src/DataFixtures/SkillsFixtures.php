<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SkillsFixtures extends Fixture
{
    protected $faker;
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        for($i = 1; $i <= 100; $i++)
        {
            $skills = new Skill();
            $skills->setName($this->faker->title);
            $skills->addUser($this->getReference(User::class.'_'. $this->faker->numberBetween(1, 90)));
            $manager->persist($skills);
        }


        $manager->flush();
    }
}
