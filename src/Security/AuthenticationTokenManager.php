<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 07 2019
 */

namespace App\Security;


use App\Entity\AuthenticationToken;
use App\Entity\Person;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class AuthenticationTokenManager
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * PasswordManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Person $user
     * @return AuthenticationToken
     * @throws Exception
     */
    public function generateAuthenticationToken(Person $user): AuthenticationToken
    {
        do {
            $collision = false;
            $string = bin2hex(random_bytes(64));

            $token = new AuthenticationToken($string, $user);

            try {
                $this->em->persist($token);
                $this->em->flush();
            } catch (UniqueConstraintViolationException $exception) {
                $collision = true;
            }
        } while ($collision);

        return $token;
    }

}