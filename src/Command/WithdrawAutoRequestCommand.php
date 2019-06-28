<?php

namespace App\Command;

use App\Entity\Withdraw;
use App\Repository\AccountRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WithdrawAutoRequestCommand extends Command
{
    protected static $defaultName = 'app:withdraw:auto-request';

    /**
     * @var AccountRepository
     */
    private $accountRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        AccountRepository $accountRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->accountRepository = $accountRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Automatically request withdraws')
            ->addOption('persist', null, InputOption::VALUE_NONE, 'Persist changes to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $accounts = $this->accountRepository->findAll();

        foreach ($accounts as $account) {

            if ($account->getBalance() == 0) {
                continue;
            }

            if (!$account->getOwner()->getBankAccount()) {
                $io->warning(sprintf('Skipping account #%s - No bank account defined', $account->getId()));
                continue;
            }

            $withdraw = new Withdraw(
                $account,
                $account->getBalance(),
                $account->getOwner()->getBankAccount(),
                null
            );

            $io->writeln(sprintf('Creating withdraw request of $%s from account #%s to bank account #%s owned by %s on %s',
                $withdraw->getValue(),
                $withdraw->getAccount()->getId(),
                $withdraw->getBankAccount()->getId(),
                $withdraw->getAccount()->getOwner()->getName(),
                (new DateTime())->format('Y-m-d H:i:s')
            ));

            $this->entityManager->persist($withdraw);
        }

        if ($input->getOption('persist')) {
            $this->entityManager->flush();
        }

        $io->success('Done');
    }
}
