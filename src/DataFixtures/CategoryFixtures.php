<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    // compteur de categories
    private $counter = 1;
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        // $parent = $this->generateCategory('Hommes', null, $manager);
        $parent = $this->generateCategory(name: 'Hommes', parent: null, manager: $manager);

        $this->generateCategory('Chemises', $parent, $manager);
        $this->generateCategory('Pantalons Jeans', $parent, $manager);
        $this->generateCategory('T-Shirts', $parent, $manager);
        $this->generateCategory('Pulls', $parent, $manager);

        $parent = $this->generateCategory(name: 'Femmes', parent: null, manager: $manager);

        $this->generateCategory('Robes', $parent, $manager);
        $this->generateCategory('Jupes', $parent, $manager);

        $manager->flush();
    }

    private function generateCategory(string $name, Category $parent = null, ObjectManager $manager) {
        $category = new Category();
        $category->setName($name)
                 ->setSlug($this->slugger->slug($category->getName())->lower())
                 ->setParent($parent);
        $manager->persist($category);

        $this->addReference('category-' .$this->counter, $category);
        $this->counter++;
        return $category;
    }
}
