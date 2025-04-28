<?php

declare(strict_types=1);

namespace Src\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class LandingController
{
    public function __construct(private Twig $twig) {}
    public function index(Response $response, array $args): Response
    {
        return $this->twig->render($response, 'landing/landing.twig', $args);
    }

    public function error(Request $request, Response $response, array $args): Response
    {
        $args = [
            'error' => $request->getQueryParams()
        ];

        return $this->twig->render($response, 'landing/error.twig', $args);
    }
}
