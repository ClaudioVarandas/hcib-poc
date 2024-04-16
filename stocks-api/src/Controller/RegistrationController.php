<?php

namespace App\Controller;

use App\Dto\RegistrationDto;
use App\Repository\UserRepository;
use Phalcon\Filter\Validation\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class RegistrationController extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload] RegistrationDto $createUserDto,
        Request $request,
        UserRepository $userRepository
    ): JsonResponse {
        $user = $userRepository->findOneBy(['email' => $createUserDto->email]);

        if ($user) {
            return $this->json(
                ['message' => 'Account already exist.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $userRepository->createUser($createUserDto);

        return $this->json(['message' => 'Registered Successfully']);
    }
}