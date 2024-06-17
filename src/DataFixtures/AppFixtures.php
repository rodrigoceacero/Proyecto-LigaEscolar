<?php

namespace App\DataFixtures;

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

        SeasonFactory::createOne([
            'description' => '2020/21',
            'startDate' => new \DateTime('2020/01/1'),
            'endDate' => new \DateTime('2021/01/1'),
        ]);

        SeasonFactory::createOne([
            'description' => '2021/22',
            'startDate' => new \DateTime('2021/01/1'),
            'endDate' => new \DateTime('2022/01/1'),
        ]);

        SeasonFactory::createOne([
            'description' => '2022/23',
            'startDate' => new \DateTime('2022/01/1'),
            'endDate' => new \DateTime('2023/01/1'),
        ]);

        SeasonFactory::createOne([
            'description' => '2023/24',
            'startDate' => new \DateTime('2023/01/1'),
            'endDate' => new \DateTime('2024/01/1'),
        ]);

        /*
            CREAR DATOS DE PRUEBA DE EQUIPOS ASIGNÁNDOLE A CADA EQUIPO UN DEPORTE
        */
        $faker = Factory::create();
        $teams = TeamFactory::createMany(100, function () {
            return [
                'seasons' => SeasonFactory::randomRange(0,4)
            ];
        });

        foreach ($teams as $team) {
            /*
                CREAR DATOS DE PRUEBA DE JUGADORES ASIGNÁNDOLES UN EQUIPO A CADA UNO HASTA UN MÁXIMO DE 10 POR EQUIPO
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

        // Crear partidos y asignarles temporadas y deportes aleatorios
        $gameMatches = GameMatchFactory::createMany(50, function() {
            return [
                'season' => SeasonFactory::random(),
                'sport' => SportFactory::random()
            ];
        });

        foreach ($gameMatches as $gameMatch) {
            $sport = $gameMatch->getSport();
            $status = $gameMatch->getStatus();

            $teamsSport = array_filter($teams, function ($team) use ($sport) {
                return $team->getSport() === $sport;
            });

            if (count($teamsSport) >= 2) {
                $teamKeys = array_rand($teamsSport, 2);
                $selectedTeams = [$teamsSport[$teamKeys[0]], $teamsSport[$teamKeys[1]]];

                $points = [0, 0];
                $score = [0, 0];
                $orderNumbers = [0, 1];

                if ($status != 0) {
                    $randomPoints = [0, 1, 3];
                    shuffle($randomPoints);

                    if ($randomPoints[0] === 3) {
                        $points = [3, 0];
                    } elseif ($randomPoints[0] === 1) {
                        $points = [1, 1];
                    } else {
                        $points = [3, 0];
                    }

                    $score = [
                        $faker->numberBetween(1, 50),
                        $faker->numberBetween(1, 50)
                    ];
                }

                foreach ($selectedTeams as $index => $team) {
                    TeamMatchGameFactory::createOne([
                        'gameMatch' => $gameMatch,
                        'team' => $team,
                        'points' => $points[$index],
                        'score' => $score[$index],
                        'orderNumber' => $orderNumbers[$index]
                    ]);
                }
            }
        }

        $manager->flush();
    }
}
