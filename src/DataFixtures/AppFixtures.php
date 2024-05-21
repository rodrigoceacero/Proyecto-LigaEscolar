<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => $this->passwordHasher->hashPassword(
                new User(),
                'admin'
            ),
            'isAdmin' => 1,
            'isDeveloper' => 0
        ]);

        UserFactory::createOne([
            'username' => 'developer',
            'email' => 'developer@developer.com',
            'password' => $this->passwordHasher->hashPassword(
                new User(),
                'developer'
            ),
            'isAdmin' => 0,
            'isDeveloper' => 1
        ]);

        UserFactory::createOne([
            'username' => 'usuario',
            'email' => 'usuario@usuario.com',
            'password' => $this->passwordHasher->hashPassword(
                new User(),
                'usuario'
            ),
            'isAdmin' => 0,
            'isDeveloper' => 0
        ]);
        
        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }
}
