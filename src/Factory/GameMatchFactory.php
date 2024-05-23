<?php

namespace App\Factory;

use App\Entity\GameMatch;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<GameMatch>
 *
 * @method        GameMatch|Proxy                  create(array|callable $attributes = [])
 * @method static GameMatch|Proxy                  createOne(array $attributes = [])
 * @method static GameMatch|Proxy                  find(object|array|mixed $criteria)
 * @method static GameMatch|Proxy                  findOrCreate(array $attributes)
 * @method static GameMatch|Proxy                  first(string $sortedField = 'id')
 * @method static GameMatch|Proxy                  last(string $sortedField = 'id')
 * @method static GameMatch|Proxy                  random(array $attributes = [])
 * @method static GameMatch|Proxy                  randomOrCreate(array $attributes = [])
 * @method static EntityRepository|RepositoryProxy repository()
 * @method static GameMatch[]|Proxy[]              all()
 * @method static GameMatch[]|Proxy[]              createMany(int $number, array|callable $attributes = [])
 * @method static GameMatch[]|Proxy[]              createSequence(iterable|callable $sequence)
 * @method static GameMatch[]|Proxy[]              findBy(array $attributes)
 * @method static GameMatch[]|Proxy[]              randomRange(int $min, int $max, array $attributes = [])
 * @method static GameMatch[]|Proxy[]              randomSet(int $number, array $attributes = [])
 */
final class GameMatchFactory extends ModelFactory
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
            'location' => self::faker()->sentence(1),
            'schedule' => self::faker()->dateTimeBetween('-1 year', '+1 year'),
            'status' => self::faker()->randomElement(['Programado', 'En curso', 'Terminado']),
            'details' => self::faker()->text(20),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(GameMatch $gameMatch): void {})
        ;
    }

    protected static function getClass(): string
    {
        return GameMatch::class;
    }
}
