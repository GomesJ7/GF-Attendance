<?php

namespace App\Factory;

use App\Entity\Matiere;
use App\Repository\MatiereRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Matiere>
 *
 * @method        Matiere|Proxy                     create(array|callable $attributes = [])
 * @method static Matiere|Proxy                     createOne(array $attributes = [])
 * @method static Matiere|Proxy                     find(object|array|mixed $criteria)
 * @method static Matiere|Proxy                     findOrCreate(array $attributes)
 * @method static Matiere|Proxy                     first(string $sortedField = 'id')
 * @method static Matiere|Proxy                     last(string $sortedField = 'id')
 * @method static Matiere|Proxy                     random(array $attributes = [])
 * @method static Matiere|Proxy                     randomOrCreate(array $attributes = [])
 * @method static MatiereRepository|RepositoryProxy repository()
 * @method static Matiere[]|Proxy[]                 all()
 * @method static Matiere[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Matiere[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Matiere[]|Proxy[]                 findBy(array $attributes)
 * @method static Matiere[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Matiere[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class MatiereFactory extends ModelFactory
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
            'nomMatiere' => self::faker()->text(30),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Matiere $matiere): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Matiere::class;
    }
}
