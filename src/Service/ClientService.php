<?php

namespace App\Service;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllClients(): array
    {
        $clients = $this->entityManager->getRepository(Client::class)->findAll();
        return array_map(function (Client $client) {
            return [
                'name'  => $client->getName(),
                'email' => $client->getEmail(),
            ];
        }, $clients);
    }

    public function getClient(int $id): ?array
    {
        $client = $this->entityManager->getRepository(Client::class)->find($id);
        if ($client) {
            return [
                'name'  => $client->getName(),
                'email' => $client->getEmail(),
            ];
        }
        return null;
    }

    public function createClient(array $data): array
    {
        $client = new Client();
        $client->setName($data['name']);
        $client->setEmail($data['email']);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return [
            'name'  => $client->getName(),
            'email' => $client->getEmail(),
        ];
    }

    public function updateClient(int $id, array $data): ?array
    {
        $client = $this->entityManager->getRepository(Client::class)->find($id);
        if (!$client) {
            return null;
        }

        $client->setName($data['name'] ?? $client->getName());
        $client->setEmail($data['email'] ?? $client->getEmail());

        $this->entityManager->flush();

        return [
            'name'  => $client->getName(),
            'email' => $client->getEmail(),
        ];
    }

    public function deleteClient(int $id): bool
    {
        $client = $this->entityManager->getRepository(Client::class)->find($id);
        if (!$client) {
            return false;
        }

        $this->entityManager->remove($client);
        $this->entityManager->flush();

        return true;
    }
}