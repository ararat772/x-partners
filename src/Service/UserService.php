<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager   = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUser(string $email, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordEncoder->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}