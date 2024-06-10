<?php

namespace App\DataFixtures;

use App\Entity\GameMatch;
use App\Entity\Season;
use App\Entity\Sport;
use App\Entity\TeamMatchGame;
use App\Entity\User;
use App\Factory\GameMatchFactory;
use App\Factory\PersonFactory;
use App\Factory\TeamFactory;
use App\Factory\TeamMatchGameFactory;
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
            'duration' => 90,
            'active' => 1
        ]);

        SportFactory::createOne([
            'name' => 'Baloncesto',
            'duration' => 40,
            'active' => 1
        ]);

        SportFactory::createOne([
            'name' => 'Fútbol sala',
            'duration' => 40,
            'active' => 1
        ]);

        SportFactory::createOne([
            'name' => 'Balonmano',
            'duration' => 60,
            'active' => 1
        ]);

        SportFactory::createOne([
            'name' => 'Voleyball',
            'duration' => 60,
            'active' => 1
        ]);

        SportFactory::createOne([
            'name' => 'Badmintón',
            'duration' => 12,
            'active' => 1
        ]);
        
        /*
            CREAR DATOS DE PRUEBA DE EQUIPOS ASIGNÁNDOLE A CADA EQUIPO UN DEPORTE
        */
        $faker = Factory::create();
        $teams = TeamFactory::createMany(5);

        foreach ($teams as $team) {
            /*
                CREAR DATOS DE PRUEBA DE JUGADORES ASIGNÁNDOLES UN EQUIPO A CADA UNO HASTA UN MÁXIMO DE 10 POR EQUIPO 
                CON UN NÚMERO DE JUGADOR QUE NO SE REPITE EN CADA EQUIPO
            */
            $hasTeacher = false;

            for ($i = 0; $i < 11; $i++) {
                $isTeacher = !$hasTeacher && $faker->boolean(20);
                $hasTeacher = $hasTeacher || $isTeacher;

                PersonFactory::createOne(
                    [
                        'team' => $team,
                        'isTeacher' => $isTeacher,
                        'isPlayer' => !$isTeacher,
                    ]
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

        GameMatchFactory::createMany(10, function(){
            return [
                'season' => SeasonFactory::random(),
                'sport' => SportFactory::random()
            ];
        });

        TeamMatchGameFactory::createMany(10 , function() {
            return [
                'gameMatch' => GameMatchFactory::random(),
                'team' => TeamFactory::random()
            ];
        });


        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }
}
