<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($p = 1; $p <= 10; $p++) {
            $product = new Product();
            $product
                ->setName($faker->text(20))
                ->setDescription($faker->text())
                ->setSlug($this->slugger->slug($product->getName())->lower())
                ->setPrice($faker->randomFloat(2, 10, 150))
                ->setQuantity($faker->numberBetween(3, 10));

            $category = $this->getReference('category-' . rand(1, 6));
            $product->setCategory($category);

            $this->addReference('product-' .$p, $product);

            $manager->persist($product);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
