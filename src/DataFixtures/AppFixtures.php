<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Establishment;
use App\Entity\Transaction;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    private array $establishment = [];

    private array $categories = [];

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->addEstablishment($manager);
        $this->addCategories($manager);
        $this->addTransactions($manager);

        $manager->flush();
    }

    public function addTransactions(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $type = ['income', 'outcome'];

        for ($i = 0; $i < 100; $i++) {
            $row = (new Transaction)
                ->setValue(rand(100, 30000))
                ->setTitle($faker->sentence(rand(1, 5)))
                ->setDescription($faker->sentence(rand(3, 8)))
                ->setEstablishment($this->establishment[rand(0, 1)])
                ->setCategory($this->categories[rand(0, count($this->categories) - 1)])
                ->setType($type[rand(0, 1)]);
            $row->setDate(new DateTimeImmutable());

            $manager->persist($row);
        }
    }

    public function addEstablishment(ObjectManager $manager): void
    {
        $banks = [
            [
                'name' => 'LCL',
                'colorCss' => 'blue'
            ],
            [
                'name' => 'Crédit Mutuel',
                'colorCss' => 'red'
            ],
        ];

        foreach ($banks as $key => $bank) {
            $establishment = new Establishment;
            $establishment->setName($bank['name']);
            $establishment->setColorCss($bank['colorCss']);

            $manager->persist($establishment);
            $this->establishment[] = $establishment;
        }
    }

    public function addCategories(ObjectManager $manager): void
    {
        $categories = [
            [
                'name' => 'Alimentation',
                'colorCss' => 'blue'
            ],
            [
                'name' => 'Santé',
                'colorCss' => 'red'
            ],
            [
                'name' => 'Loyer',
                'colorCss' => 'red'
            ],
        ];

        foreach ($categories as $key => $cat) {
            $category = new Category;
            $category->setName($cat['name']);
            $category->setColorCss($cat['colorCss']);

            $manager->persist($category);
            $this->categories[] = $category;
        }
    }
}
