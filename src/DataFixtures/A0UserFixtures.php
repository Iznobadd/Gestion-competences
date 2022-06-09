<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class A0UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ADD COLLABORATOR USER
        for($i = 1; $i <= 50; $i++)
        {
            $user = new User();
            $user->setRoles(['ROLE_COLLAB']);
            $user->setIsCommercial(false);
            $user->setIsCollab(true);
            $user->setIsAdmin(false);
            $user->setEmail('collab' . $i . '@gmail.com');
            $user->setFirstName('Collaborator');
            $user->setLastName($i);
            $user->setPassword('123456');
            $user->setStatus(true);
            $this->addReference(User::class.'_'.$i, $user);
            $manager->persist($user);
        }

        // ADD SALES USER
        for($i = 51; $i <= 70; $i++)
        {
            $user = new User();
            $user->setRoles(['ROLE_SALE']);
            $user->setIsCommercial(true);
            $user->setIsCollab(false);
            $user->setIsAdmin(false);
            $user->setEmail('sales' . $i . '@gmail.com');
            $user->setFirstName('SalesMan');
            $user->setLastName($i);
            $user->setPassword('123456');
            $user->setStatus(true);
            $this->addReference(User::class.'_'.$i, $user);
            $manager->persist($user);
        }

        // ADD ADMIN USER
        for($i = 71; $i <= 80; $i++)
        {
            $user = new User();
            $user->setRoles(['ROLE_ADMIN']);
            $user->setIsCommercial(false);
            $user->setIsCollab(false);
            $user->setIsAdmin(true);
            $user->setEmail('admin' . $i . '@gmail.com');
            $user->setFirstName('Admin');
            $user->setLastName($i);
            $user->setPassword('123456');
            $user->setStatus(true);
            $this->addReference(User::class.'_'.$i, $user);
            $manager->persist($user);
        }

        // ADD CANDIDATE USER
        for($i = 81; $i <= 90; $i++)
        {
            $user = new User();
            $user->setRoles(['ROLE_USER']);
            $user->setIsCommercial(false);
            $user->setIsCollab(false);
            $user->setIsAdmin(false);
            $user->setEmail('candidate' . $i . '@gmail.com');
            $user->setFirstName('Candidate');
            $user->setLastName($i);
            $user->setPassword('123456');
            $user->setStatus(false);
            $this->addReference(User::class.'_'.$i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
