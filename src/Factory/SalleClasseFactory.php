<?php

namespace App\Factory;

use App\Entity\SalleClasse;
use App\Repository\SalleClasseRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<SalleClasse>
 *
 * @method        SalleClasse|Proxy                     create(array|callable $attributes = [])
 * @method static SalleClasse|Proxy                     createOne(array $attributes = [])
 * @method static SalleClasse|Proxy                     find(object|array|mixed $criteria)
 * @method static SalleClasse|Proxy                     findOrCreate(array $attributes)
 * @method static SalleClasse|Proxy                     first(string $sortedField = 'id')
 * @method static SalleClasse|Proxy                     last(string $sortedField = 'id')
 * @method static SalleClasse|Proxy                     random(array $attributes = [])
 * @method static SalleClasse|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SalleClasseRepository|RepositoryProxy repository()
 * @method static SalleClasse[]|Proxy[]                 all()
 * @method static SalleClasse[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static SalleClasse[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static SalleClasse[]|Proxy[]                 findBy(array $attributes)
 * @method static SalleClasse[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static SalleClasse[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SalleClasseFactory extends ModelFactory
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
            'adresse' => self::faker()->text(250),
            'nomSalle' => self::faker()->text(30),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(SalleClasse $salleClasse): void {})
        ;
    }

    protected static function getClass(): string
    {
        return SalleClasse::class;
    }
}
