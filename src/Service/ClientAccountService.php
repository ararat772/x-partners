<?php

namespace App\Service;

use App\Entity\ClientAccount;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClientAccountService
{
    private $entityManager;
    private $clientRepository;

    public function __construct(EntityManagerInterface $entityManager, ClientRepository $clientRepository)
    {
        $this->entityManager    = $entityManager;
        $this->clientRepository = $clientRepository;
    }

    public function getAllAccounts(): array
    {
        return $this->entityManager->getRepository(ClientAccount::class)->findAll();
    }

    public function createAccount(array $data): ClientAccount
    {
        $client = $this->clientRepository->find($data['clientId']);
        if (!$client) {
            throw new \Exception('Client not found');
        }

        $clientAccount = new ClientAccount();
        $clientAccount->setClient($client);
        $clientAccount->setCurrency($data['currency']);
        $clientAccount->setBalance($data['balance']);

        $this->entityManager->persist($clientAccount);
        $this->entityManager->flush();

        return $clientAccount;
    }

    public function updateAccount(ClientAccount $clientAccount, array $data): ClientAccount
    {
        $clientAccount->setCurrency($data['currency'] ?? $clientAccount->getCurrency());
        $clientAccount->setBalance($data['balance'] ?? $clientAccount->getBalance());

        $this->entityManager->flush();

        return $clientAccount;
    }

    public function deleteAccount(ClientAccount $clientAccount): void
    {
        $this->entityManager->remove($clientAccount);
        $this->entityManager->flush();
    }
}