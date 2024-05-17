<?php

namespace App\Factory;

use App\Entity\Season;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Season>
 *
 * @method        Season|Proxy                     create(array|callable $attributes = [])
 * @method static Season|Proxy                     createOne(array $attributes = [])
 * @method static Season|Proxy                     find(object|array|mixed $criteria)
 * @method static Season|Proxy                     findOrCreate(array $attributes)
 * @method static Season|Proxy                     first(string $sortedField = 'id')
 * @method static Season|Proxy                     last(string $sortedField = 'id')
 * @method static Season|Proxy                     random(array $attributes = [])
 * @method static Season|Proxy                     randomOrCreate(array $attributes = [])
 * @method static EntityRepository|RepositoryProxy repository()
 * @method static Season[]|Proxy[]                 all()
 * @method static Season[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Season[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Season[]|Proxy[]                 findBy(array $attributes)
 * @method static Season[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Season[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SeasonFactory extends ModelFactory
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
            'description' => self::faker()->text(),
            'endDate' => self::faker()->dateTime(),
            'startDate' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Season $season): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Season::class;
    }
}
