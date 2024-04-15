<?php

namespace App\Controller;

use App\Dto\RegistrationDto;
use App\Repository\UserRepository;
use Phalcon\Filter\Validation\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class RegistrationController extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload] RegistrationDto $createUserDto,
        ManagerRegistry $doctrine,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ): JsonResponse {
        $em = $doctrine->getManager();

        $user = $userRepository->findOneBy(['email' => $createUserDto->email]);

        if($user){
            return $this->json(['message' => 'User already exist with that criteria.'],400);
        }

        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $createUserDto->password
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($createUserDto->email);
        $user->setName($createUserDto->name);

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Registered Successfully']);
    }
}