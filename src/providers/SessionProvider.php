<?php

declare(strict_types=1);

namespace Src\Providers;

use DateTime;
use Doctrine\ORM\EntityManager;
use Src\Entities\Sessions;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionProvider
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly SessionInterface $session
    ) {}
    public function getById(string $id): Sessions|null
    {
        return $this->entityManager->find(Sessions::class, $id);
    }

    public function getByUser(int $user): Sessions|null
    {
        return $this->entityManager->getRepository(Sessions::class)
            ->findOneBy(['user_id' => $user]);
    }

    public function store(Sessions $session, string $sessionId, $userAgent, $IpAddress): void
    {
        $session
            ->setId($sessionId)
            ->setIpAddress($IpAddress)
            ->setUserAgent($userAgent)
            ->setLastAction(new DateTime());

        if ($user = $this->session->get('userEntity')) {
            $session->setUser($user);
        }

        $this->sync($session);
    }

    public function update(Sessions $session, DateTime $dateTime): void
    {
        $session->setLastAction($dateTime);

        $this->sync($session);
    }

    public function sync(Sessions $session): void
    {
        $this->entityManager->persist($session);
        $this->entityManager->flush();
    }
}
