<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\UtilisateurFactory;
use App\Factory\SalleClasseFactory;
use App\Factory\MatiereFactory;
use App\Factory\FormationFactory;
use App\Factory\SessionFactory;
use App\Factory\PromotionFactory;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        UtilisateurFactory::createOne();
        $manager->flush();
    }
}
