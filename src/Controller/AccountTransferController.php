<?php

namespace App\Controller;

use App\Service\AccountTransferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class AccountTransferController extends AbstractController
{
    private $transferService;

    public function __construct(AccountTransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    #[Route('/transfer', name: 'account_transfer', methods: ['POST'])]
    public function transfer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $result = $this->transferService->validateAndTransfer($data);

            return $this->json([
                'message'               => 'Transfer successful',
                'fromAccountNewBalance' => $result['fromAccountNewBalance'],
                'toAccountNewBalance'   => $result['toAccountNewBalance']
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}