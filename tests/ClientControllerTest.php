<?php

use App\Entity\Client;
use App\Service\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClientControllerTest extends KernelTestCase
{
    private ClientService $clientService;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->clientService = static::getContainer()->get(ClientService::class);

        $this->entityManager->createQuery('DELETE FROM App\Entity\Client')->execute();
    }

    public function testGetAllClients()
    {
        $client1 = new Client();
        $client1->setName('Client 1');
        $client1->setEmail('client1@example.com');
        $this->entityManager->persist($client1);

        $client2 = new Client();
        $client2->setName('Client 2');
        $client2->setEmail('client2@example.com');
        $this->entityManager->persist($client2);

        $this->entityManager->flush();

        $clients = $this->clientService->getAllClients();

        $this->assertCount(2, $clients);
        $this->assertEquals('Client 1', $clients[0]['name']);
        $this->assertEquals('Client 2', $clients[1]['name']);
    }

    public function testGetClient()
    {
        $client = new Client();
        $client->setName('Test Client');
        $client->setEmail('test@example.com');
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $foundClient = $this->clientService->getClient($client->getId());

        $this->assertNotNull($foundClient);
        $this->assertEquals('Test Client', $foundClient['name']);
        $this->assertEquals('test@example.com', $foundClient['email']);
    }


    public function testUpdateClient()
    {
        $client = new Client();
        $client->setName('Original Client');
        $client->setEmail('original@example.com');
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $updatedData = [
            'name'  => 'Updated Client',
            'email' => 'updated@example.com',
        ];

        $updatedClient = $this->clientService->updateClient($client->getId(), $updatedData);

        $this->assertNotNull($updatedClient);
        $this->assertEquals($updatedData['name'], $updatedClient['name']);
        $this->assertEquals($updatedData['email'], $updatedClient['email']);
    }

    public function testDeleteClient()
    {
        $client = new Client();
        $client->setName('Client to Delete');
        $client->setEmail('delete@example.com');
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $clientId = $client->getId();

        $deleted = $this->clientService->deleteClient($clientId);

        $this->assertTrue($deleted);

        $deletedClient = $this->entityManager->getRepository(Client::class)->find($clientId);
        $this->assertNull($deletedClient);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}