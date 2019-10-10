<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael@enhardened.com>, 07 2019
 */

namespace App\Helper;


use App\Entity\Person;
use App\Security\AuthenticationTokenManager;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PasswordHelper
{
    /**
     * @var AuthenticationTokenManager
     */
    private $tokenManager;

    /**
     * @var EmailHelper
     */
    private $mailer;

    public function __construct(AuthenticationTokenManager $tokenManager, EmailHelper $mailer)
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
        $email = $this->mailer->createTemplatedEmail(
            $person->getEmail(),
            'Definição de Senha',
            '_emails/password-definition.html.twig',
            [
                'user' => $person,
                'token' => $this->tokenManager->generateAuthenticationToken($person),
            ]
        );

        $this->mailer->send($email);
    }
}