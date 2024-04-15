<?php

namespace App\Controller;

use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class HistoryController extends AbstractController
{
    public function __construct(
        private readonly HistoryRepository $historyRepository
    ) {
    }

    public function index(): JsonResponse
    {
        $data = $this->historyRepository->findBy(
            ['user' => $this->getUser()->getId()],
            ['date' => 'DESC']
        );

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('list_history')
            ->toArray();

        return $this->json($data, Response::HTTP_OK, [], $context);
    }
}
