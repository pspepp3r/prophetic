<?php

declare(strict_types=1);

namespace Src\Middlewares;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Src\Services\AuthService;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly AuthService $auth,
        private readonly EntityManager $entityManager,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly Twig $twig
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$user = $this->auth->user()) {
            return $this->responseFactory->createResponse(302)->withHeader('Location', '/');
        }

        $this->twig->getEnvironment()->addGlobal('user', $user);
        $this->twig->getEnvironment()->addGlobal(
            'current_route',
            RouteContext::fromRequest($request)->getRoute()->getName()
        );

        return $handler->handle($request);
    }
}
