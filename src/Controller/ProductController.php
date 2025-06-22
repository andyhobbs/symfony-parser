<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class ProductController
{

    public function __construct(
        private SerializerInterface $serializer,
        private ProductRepository $repository,
    ) {}

    #[Route('/products', name: 'get_products', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $json = $this->serializer->serialize($this->repository->findAll(), 'json');

        return new JsonResponse($json, 200, [], true);
    }
}
