<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 07 2019
 */

namespace App\Helper;


use App\Entity\Person;
use App\Security\AuthenticationTokenManager;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\NamedAddress;

class PasswordHelper
{
    /**
     * @var AuthenticationTokenManager
     */
    private $tokenManager;
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(AuthenticationTokenManager $tokenManager, MailerInterface $mailer)
    {
        $this->tokenManager = $tokenManager;
        $this->mailer = $mailer;
    }

    /**
     * @param Person $person
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function sendPasswordDefinitionEmail(Person $person)
    {
        $email = (new TemplatedEmail())
            ->from(new NamedAddress('sistema@adinvest.com', 'AdInvest'))
            ->to($person->getEmail())
            ->replyTo('rafaelsouza@adinvest.com')
            ->subject('Definição de Senha - AdInvest')
            ->htmlTemplate('_emails/password-definition.html.twig')
            ->context([
                'user' => $person,
                'token' => $this->tokenManager->generateAuthenticationToken($person),
            ]);

        $this->mailer->send($email);
    }
}