<?php
declare(strict_types=1);

namespace Src\Controllers;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class LandingController
{
  public function __construct(private Twig $twig)
  {
  }
  public function index(Request $request, Response $response, array $args): Response
  {
    $args = [
      'title' => 'Project | Home',
  ];
    return $this->twig->render($response, 'landing/landing.twig', $args);
  }
}
