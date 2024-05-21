<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\PersonFactory;
use App\Factory\TeamFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        /*
            CREAR DATOS DE PRUEBA DE USUARIOS
        */
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

        /*
            CREAR DATOS DE PRUEBA DE EQUIPOS
        */
        $faker = Factory::create();
        $teams = TeamFactory::createMany(5);

        foreach ($teams as $team) {
            /*
                CREAR DATOS DE PRUEBA DE JUGADORES ASIGNÁNDOLES UN EQUIPO A CADA UNO HASTA UN MÁXIMO DE 10 POR EQUIPO 
                CON UN NÚMERO DE JUGADOR QUE NO SE REPITE EN CADA EQUIPO
            */
            $usedNumbers = []; 

            for ($i = 0; $i < 10; $i++) {
                do {
                    $number = $faker->numberBetween(1, 15);
                } while (in_array($number, $usedNumbers)); 

                $usedNumbers[] = $number;
                PersonFactory::createOne(
                    ['team' => $team,
                    'number' => $number]
                );
            }
        }
        
        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }
}
