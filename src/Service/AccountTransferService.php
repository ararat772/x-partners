<?php

namespace App\Service;

use App\Repository\ClientAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class AccountTransferService
{
    private $entityManager;
    private $accountRepository;

    public function __construct(EntityManagerInterface $entityManager, ClientAccountRepository $accountRepository)
    {
        $this->entityManager     = $entityManager;
        $this->accountRepository = $accountRepository;
    }

    /**
     * @throws Exception
     */
    public function transfer(int $fromAccountId, int $toAccountId, float $amount): array
    {
        $fromAccount = $this->accountRepository->find($fromAccountId);
        $toAccount   = $this->accountRepository->find($toAccountId);

        if (!$fromAccount || !$toAccount) {
            throw new Exception('Account not found');
        }

        if ($fromAccount->getClient()->getId() !== $toAccount->getClient()->getId()) {
            throw new Exception('Accounts belong to different clients');
        }

        if ($fromAccount->getBalance() < $amount) {
            throw new Exception('Insufficient funds');
        }

        $exchangeRate    = $this->getExchangeRate($fromAccount->getCurrency(), $toAccount->getCurrency());
        $convertedAmount = $amount * $exchangeRate;

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $fromAccount->setBalance($fromAccount->getBalance() - $amount);
            $toAccount->setBalance($toAccount->getBalance() + $convertedAmount);

            $this->entityManager->persist($fromAccount);
            $this->entityManager->persist($toAccount);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();

            return [
                'fromAccountNewBalance' => $fromAccount->getBalance(),
                'toAccountNewBalance'   => $toAccount->getBalance()
            ];
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }

    private function getExchangeRate(string $fromCurrency, string $toCurrency): float
    {
        $exchangeRates = [
            'RUB' => 4.0,
            'USD' => 390,
            'EUR' => 430,
        ];

        return $exchangeRates[$fromCurrency] / $exchangeRates[$toCurrency];
    }

    /**
     * @throws Exception
     */
    public function validateAndTransfer(array $data): array
    {
        $fromAccountId = $data['fromAccountId'] ?? null;
        $toAccountId = $data['toAccountId'] ?? null;
        $amount = $data['amount'] ?? null;

        if (!$fromAccountId || !$toAccountId || !$amount) {
            throw new Exception('Invalid request data');
        }

        if ($fromAccountId === $toAccountId) {
            throw new Exception('Cannot transfer to the same account');
        }

        return $this->transfer($fromAccountId, $toAccountId, $amount);
    }

}