<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityNotFoundException;
use Faker;
;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++)
        {
            // Récupérer une catégorie existante depuis la base de données
            $categoryRepository = $manager->getRepository(Category::class);
            $cat = rand(1,9);
            $category = $categoryRepository->findOneBy(['id' => $cat]);

            // Vérifier si la catégorie existe
            if ($category)
            {
                $wish = new Wish();
                $wish->setTitle($faker->word);
                $wish->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true));
                $wish->setAuthor($faker->name);
                $wish->setIsPublished($faker->boolean);
                $wish->setDateCreated($faker->dateTimeInInterval($startDate = '-15 days', $interval = '+30 days', timezone: null));
                $wish->setCategory($category);
                $manager->persist($wish);
            }
            else
            {
                throw new EntityNotFoundException('Cette catégorie n\'existe pas. Désolé !');
            }
        }
        $manager->flush();
    }
}
