<?php

namespace App\Factory;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Session>
 *
 * @method        Session|Proxy                     create(array|callable $attributes = [])
 * @method static Session|Proxy                     createOne(array $attributes = [])
 * @method static Session|Proxy                     find(object|array|mixed $criteria)
 * @method static Session|Proxy                     findOrCreate(array $attributes)
 * @method static Session|Proxy                     first(string $sortedField = 'id')
 * @method static Session|Proxy                     last(string $sortedField = 'id')
 * @method static Session|Proxy                     random(array $attributes = [])
 * @method static Session|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SessionRepository|RepositoryProxy repository()
 * @method static Session[]|Proxy[]                 all()
 * @method static Session[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Session[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Session[]|Proxy[]                 findBy(array $attributes)
 * @method static Session[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Session[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SessionFactory extends ModelFactory
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
            'dateSession' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'heureDebut' => \DateTimeImmutable::createFromMutable(self::faker()->datetime()),
            'heureFin' => \DateTimeImmutable::createFromMutable(self::faker()->datetime()),
            'intitule' => self::faker()->text(50),
            'matiere' => MatiereFactory::new(),
            'salleClasse' => SalleClasseFactory::new(),
            'utilisateur' => UtilisateurFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Session $session): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Session::class;
    }
}
