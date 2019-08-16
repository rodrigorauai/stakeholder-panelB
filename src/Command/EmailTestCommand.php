<?php

namespace App\Command;

use App\Helper\EmailHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailTestCommand extends Command
{
    protected static $defaultName = 'app:email:test';

    /**
     * @var EmailHelper
     */
    private $mailer;

    public function __construct(EmailHelper $mailer, string $name = null)
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

        $email = $this->mailer->createEmail($to, 'Testing e-mail sending from Symfony Mailer!', 'Sending emails is fun again!');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $io->error($exception->getMessage());
            return;
        }

        $io->success('The message should have been sent. It may take a while to be delivered...');
    }
}
