<?php

namespace App\Controller;

use App\Entity\ClientAccount;
use App\Service\ClientAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client-accounts')]
class ClientAccountController extends AbstractController
{
    private $clientAccountService;

    public function __construct(ClientAccountService $clientAccountService)
    {
        $this->clientAccountService = $clientAccountService;
    }

    #[Route('/', name: 'client_account_index', methods: ['GET'])]
    public function index(): Response
    {
        $clientAccounts = $this->clientAccountService->getAllAccounts();
        return $this->json($clientAccounts);
    }

    #[Route('/{id}', name: 'client_account_show', methods: ['GET'])]
    public function show(ClientAccount $clientAccount): Response
    {
        return $this->json($clientAccount);
    }

    #[Route('/', name: 'client_account_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $clientAccount = $this->clientAccountService->createAccount($data);
            return $this->json($clientAccount, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'client_account_update', methods: ['PUT'])]
    public function update(Request $request, ClientAccount $clientAccount): Response
    {
        $data           = json_decode($request->getContent(), true);
        $updatedAccount = $this->clientAccountService->updateAccount($clientAccount, $data);
        return $this->json($updatedAccount);
    }

    #[Route('/{id}', name: 'client_account_delete', methods: ['DELETE'])]
    public function delete(ClientAccount $clientAccount): Response
    {
        $this->clientAccountService->deleteAccount($clientAccount);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}