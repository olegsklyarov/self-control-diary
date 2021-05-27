<?php

declare(strict_types=1);

namespace App\Controller\Mencho\PostSamayaCollection;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/mencho/samaya/collection', name: 'post_samaya_collection', methods: ['POST'])]
final class Controller
{
    public function __invoke(): Response
    {
        return new Response();
    }
}
