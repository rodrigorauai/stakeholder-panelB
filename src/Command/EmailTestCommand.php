<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailTestCommand extends Command
{
    protected static $defaultName = 'app:email:test';

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer, string $name = null)
    {
        parent::__construct($name);
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Send a test e-mail')
            ->addArgument('to', InputArgument::REQUIRED, 'Destination address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $to = $input->getArgument('to');

        $email = (new Email())
            ->from('sistema@adinvest.com')
            ->to($to)
            ->subject('Testing e-mail sending from Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $io->error($exception->getMessage());
            return;
        }

        $io->success('The message should have been sent. It may take a while to be delivered...');
    }
}
