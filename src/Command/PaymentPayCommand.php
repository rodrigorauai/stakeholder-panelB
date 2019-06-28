<?php

namespace App\Command;

use App\Repository\PaymentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PaymentPayCommand extends Command
{
    protected static $defaultName = 'app:payment:pay';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PaymentRepository $paymentRepository,
        string $name = null
    ) {
        parent::__construct($name);

        $this->paymentRepository = $paymentRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Make scheduled payments')
            ->addOption('persist', 's', InputOption::VALUE_NONE, 'Persist changes to database')
            ->addOption('date', null, InputOption::VALUE_REQUIRED, 'Changes the maximum due date to be queried')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $tomorrow = new DateTime('tomorrow');

        if ($date = $input->getOption('date')) {
            try {
                $simulatedDate = (new DateTime($date));
            } catch (Exception $exception) {
                $io->error('Unable to understand --date argument');
                die();
            }

            if ($simulatedDate >= $tomorrow) {
                $io->error('--date argument must not be in the future');
                die();
            }

            $tomorrow = $simulatedDate->modify('tomorrow');
        }

        $io->warning('Maximum queried due date set to: ' . $tomorrow->format('Y-m-d H:i:s'));

        do {
            $answer = strtolower($io->ask('Should I proceed?', 'No'));
        } while (!in_array($answer, ['yes', 'no']));

        if ($answer === 'no') {
            $io->success('Ok, stopping now.');
            die();
        }

        $payments = $this->paymentRepository->findUnpaidDueBefore($tomorrow);

        $io->writeln(sprintf('%s payments found', count($payments)));

        foreach ($payments as $payment) {

            if (!$payment->canBeMade()) {
                $io->warning(sprintf('Payment #%s cannot be made - status: %s',
                    $payment->getId(),
                    $payment->getStatus()
                ));

                continue;
            }

            $io->writeln(sprintf('Adding $%s to account #%s owned by %s on %s from payment #%s as %s',
                $payment->getValue(),
                $payment->getBeneficiary()->getId(),
                $payment->getBeneficiary()->getOwner()->getName(),
                (new DateTime())->format('Y-m-d H:i:s'),
                $payment->getId(),
                $payment->getProvenance()
            ));


            $payment->getBeneficiary()->addBalance($payment->getValue());
            $payment->setWasMade(true);

            $this->entityManager->persist($payment);
        }

        if ($input->getOption('persist')) {
            $this->entityManager->flush();
        }
    }
}
