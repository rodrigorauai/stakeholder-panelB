<?php

namespace App\Command;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAddCommand extends Command
{
    protected static $defaultName = 'app:user:add';

    /** @var EntityManagerInterface */
    protected $em;

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, string $name = null)
    {
        parent::__construct($name);

        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a new user to the system')
            ->addArgument('name', InputArgument::REQUIRED, "User's full name")
            ->addArgument('email', InputArgument::REQUIRED, "User's e-mail address")
            ->addArgument('identity', InputArgument::REQUIRED, "Number on the user's identity document")
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, "User's password")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');
        $email = $input->getArgument('email');
        $identity = $input->getArgument('identity');

        $user = new Person($name, $email);
        $user->setCpf($identity);

        if ($password = $input->getOption('password')) {
            $user->setPassword($this->encoder->encodePassword($user, $password));
        }

        $this->em->persist($user);
        $this->em->flush();

        $io->success(sprintf('New user created: #%s - %s', $user->getId(), $user->getCpf()));
    }
}