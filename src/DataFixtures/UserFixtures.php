<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
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
            $manager->persist($user);
        }

        // ADD SALES USER
        for($i = 1; $i <= 20; $i++)
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
            $manager->persist($user);
        }

        // ADD ADMIN USER
        for($i = 1; $i <= 10; $i++)
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
            $manager->persist($user);
        }


        $manager->flush();
    }
}
