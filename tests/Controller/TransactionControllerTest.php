<?php

namespace App\Tests\Controller;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TransactionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/transaction/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Transaction::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Transaction index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'transaction[value]' => 'Testing',
            'transaction[title]' => 'Testing',
            'transaction[description]' => 'Testing',
            'transaction[date]' => 'Testing',
            'transaction[type]' => 'Testing',
            'transaction[bank]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Transaction();
        $fixture->setValue('My Title');
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDate('My Title');
        $fixture->setType('My Title');
        $fixture->setBank('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Transaction');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Transaction();
        $fixture->setValue('Value');
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setDate('Value');
        $fixture->setType('Value');
        $fixture->setBank('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'transaction[value]' => 'Something New',
            'transaction[title]' => 'Something New',
            'transaction[description]' => 'Something New',
            'transaction[date]' => 'Something New',
            'transaction[type]' => 'Something New',
            'transaction[bank]' => 'Something New',
        ]);

        self::assertResponseRedirects('/transaction/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getValue());
        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getBank());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Transaction();
        $fixture->setValue('Value');
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setDate('Value');
        $fixture->setType('Value');
        $fixture->setBank('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/transaction/');
        self::assertSame(0, $this->repository->count([]));
    }
}
