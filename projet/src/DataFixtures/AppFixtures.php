<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setNom('عقود');
        $manager->persist($category);
        $category = new Category();
        $category->setNom('تقارير');
        $manager->persist($category);
        $category = new Category();
        $category->setNom('مراسالت');
        $manager->persist($category);
        $category = new Category();
        $category->setNom('فواتير');
        $manager->persist($category);
        $category = new Category();
        $category->setNom(' سياسات وإجراءات');
        $manager->persist($category);
        $category = new Category();
        $category->setNom(' وثائق أخرى');
        $manager->persist($category);

        $manager->flush();
    }
}
