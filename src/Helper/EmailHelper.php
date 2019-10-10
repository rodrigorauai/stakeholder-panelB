<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael@enhardened.com>, 08 2019
 */

namespace App\Helper;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\NamedAddress;
use Symfony\Component\Mime\RawMessage;

class EmailHelper
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var string|NamedAddress
     */
    private $from;

    /**
     * @var string|NamedAddress
     */
    private $replyTo;

    public function __construct(
        MailerInterface $mailer,
        string $fromAddress,
        ?string $fromName = null,
        ?string $replyToName = null,
        ?string $replyToAddress = null
    ) {
        $this->mailer = $mailer;

        if (!$fromName) {
            $this->from = $fromAddress;
        } else {
            $this->from = new NamedAddress($fromAddress, $fromName);
        }

        if ($replyToAddress) {

            if (!$replyToName) {
                $this->replyTo = $replyToAddress;
            } else {
                $this->replyTo = new NamedAddress($replyToAddress, $replyToName);
            }

        }
    }

    /**
     * @param string|NamedAddress $to
     * @param string $subject
     * @param string $template
     * @param array $context
     * @return TemplatedEmail
     */
    public function createTemplatedEmail($to, string $subject, string $template, array $context = [])
    {
        $email = new TemplatedEmail();
        $email->from($this->from);

        if ($this->replyTo) {
            $email->replyTo($this->replyTo);
        }

        $email->to($to);
        $email->subject($subject);

        $email->htmlTemplate($template);
        $email->context($context);

        return $email;
    }

    public function createEmail($to, string $subject, string $content)
    {
        $email = new Email();
        $email->from($this->from);

        if ($this->replyTo) {
            $email->replyTo($this->replyTo);
        }

        $email->to($to);
        $email->subject($subject);

        $email->html($content);

        return $email;
    }

    /**
     * @param RawMessage $email
     * @throws TransportExceptionInterface
     */
    public function send(RawMessage $email)
    {
        $this->mailer->send($email);
    }
}