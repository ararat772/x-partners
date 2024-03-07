<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    private UserService $userService;
    private LoggerInterface $logger;

    public function __construct(
        UserService $userService,
        LoggerInterface $logger,
        protected UserRepository $userRepository
    ) {
        $this->userService = $userService;
        $this->logger      = $logger;
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->userService->createUser($data['email'], $data['password']);

            return $this->json(['message' => 'User registered successfully']);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return $this->json(['error' => 'Registration failed'], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data     = json_decode($request->getContent(), true);
        $email    = $data['email'];
        $password = $data['password'];

        try {
            $user = $this->userRepository->getByEmail($email);
            if (!$passwordHasher->isPasswordValid($user, $password)) {
                throw new \Exception('Invalid credentials');
            }

            $token = $jwtManager->create($user);

            return $this->json(['token' => $token]);
        } catch (\Exception $e) {

            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }
}
