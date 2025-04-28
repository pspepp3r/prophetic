<?php

declare(strict_types=1);

namespace Src\Mails;

use Src\Entities\PasswordReset;
use Src\Services\ConfigService;
use Src\Services\SignedUrlService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;

class ForgotPasswordEmail
{
    public function __construct(
        private readonly ConfigService $config,
        private readonly MailerInterface $mailer,
        private readonly BodyRendererInterface $renderer,
        private readonly SignedUrlService $signedUrl
    ) {}

    public function send(PasswordReset $passwordReset): void
    {
        $email   = $passwordReset->getEmail();
        $resetLink = $this->signedUrl->fromRoute(
            'password-reset',
            ['token' => $passwordReset->getToken()],
            $passwordReset->getExpiration()
        );
        $message = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($email)
            ->subject('Your Blogginit Password Reset Link')
            ->htmlTemplate('emails/reset_password.html.twig')
            ->context(
                [
                    'resetLink' => $resetLink,
                ]
            );

        $this->renderer->render($message);

        $this->mailer->send($message);
    }
}
