<?php

namespace App\DataFixtures;

use App\Entity\Sport;
use App\Entity\User;
use App\Factory\PersonFactory;
use App\Factory\TeamFactory;
use App\Factory\UserFactory;
use App\Factory\SportFactory;
use App\Factory\SeasonFactory;
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
            CREAR DATOS DE PRUEBA DE DEPORTES
        */
        SportFactory::createOne([
            'name' => 'Fútbol 7',
            'duration' => 90
        ]);

        SportFactory::createOne([
            'name' => 'Baloncesto',
            'duration' => 40
        ]);

        SportFactory::createOne([
            'name' => 'Fútbol sala',
            'duration' => 40
        ]);

        SportFactory::createOne([
            'name' => 'Balonmano',
            'duration' => 60
        ]);

        SportFactory::createOne([
            'name' => 'Voleyball',
            'duration' => 60
        ]);

        SportFactory::createOne([
            'name' => 'Badmintón',
            'duration' => 12
        ]);
        
        /*
            CREAR DATOS DE PRUEBA DE EQUIPOS ASIGNÁNDO A CADA EQUIPO UN DEPORTE
        */
        $faker = Factory::create();
        $teams = TeamFactory::createMany(5, function() {
            return [
                'sport' => SportFactory::random()
            ];
        });

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
        
        SeasonFactory::createOne([
            'description' => '2020/21',
            'startDate' => new \DateTime('2020/01/1'),
            'endDate' => new \DateTime('2021/01/1')
        ]);

        SeasonFactory::createOne([
            'description' => '2021/22',
            'startDate' => new \DateTime('2021/01/1'),
            'endDate' => new \DateTime('2022/01/1')
        ]);

        SeasonFactory::createOne([
            'description' => '2022/23',
            'startDate' => new \DateTime('2022/01/1'),
            'endDate' => new \DateTime('2023/01/1')
        ]);

        SeasonFactory::createOne([
            'description' => '2023/24',
            'startDate' => new \DateTime('2023/01/1'),
            'endDate' => new \DateTime('2024/01/1')
        ]);
        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }
}
