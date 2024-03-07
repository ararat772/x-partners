<?php

namespace App\Controller;

use App\Service\ClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    private ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(): Response
    {
        $clients = $this->clientService->getAllClients();
        return $this->json($clients);
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $client = $this->clientService->getClient($id);
        if (!$client) {
            return $this->json(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($client);
    }

    #[Route('/', name: 'client_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data   = json_decode($request->getContent(), true);
        $client = $this->clientService->createClient($data);
        return $this->json($client, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'client_update', methods: ['PUT'])]
    public function update(Request $request, int $id): Response
    {
        $data          = json_decode($request->getContent(), true);
        $updatedClient = $this->clientService->updateClient($id, $data);
        if (!$updatedClient) {
            return $this->json(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($updatedClient);
    }

    #[Route('/{id}', name: 'client_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $deleted = $this->clientService->deleteClient($id);
        if (!$deleted) {
            return $this->json(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}