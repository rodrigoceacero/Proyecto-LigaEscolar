<?php

namespace App\Factory;

use App\Entity\TeamMatchGame;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TeamMatchGame>
 *
 * @method        TeamMatchGame|Proxy              create(array|callable $attributes = [])
 * @method static TeamMatchGame|Proxy              createOne(array $attributes = [])
 * @method static TeamMatchGame|Proxy              find(object|array|mixed $criteria)
 * @method static TeamMatchGame|Proxy              findOrCreate(array $attributes)
 * @method static TeamMatchGame|Proxy              first(string $sortedField = 'id')
 * @method static TeamMatchGame|Proxy              last(string $sortedField = 'id')
 * @method static TeamMatchGame|Proxy              random(array $attributes = [])
 * @method static TeamMatchGame|Proxy              randomOrCreate(array $attributes = [])
 * @method static EntityRepository|RepositoryProxy repository()
 * @method static TeamMatchGame[]|Proxy[]          all()
 * @method static TeamMatchGame[]|Proxy[]          createMany(int $number, array|callable $attributes = [])
 * @method static TeamMatchGame[]|Proxy[]          createSequence(iterable|callable $sequence)
 * @method static TeamMatchGame[]|Proxy[]          findBy(array $attributes)
 * @method static TeamMatchGame[]|Proxy[]          randomRange(int $min, int $max, array $attributes = [])
 * @method static TeamMatchGame[]|Proxy[]          randomSet(int $number, array $attributes = [])
 */
final class TeamMatchGameFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'orderNumber' => self::faker()->boolean(),
            'points' => self::faker()->randomElement([0,1,3]),
            'score' => self::faker()->numberBetween(1,50),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(TeamMatchGame $teamMatchGame): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TeamMatchGame::class;
    }
}
