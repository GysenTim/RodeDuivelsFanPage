<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-users',
    description: 'Import users from a CSV file')]
class ImportUsersCommand extends Command
{
    private $userRepository;
    private $em;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::REQUIRED, 'The path to the CSV file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Importing users...');
        $path = $input->getArgument('path');

        if (!file_exists($path) || !is_readable($path)) {
            $output->writeln('file not found or not readable');

            return Command::FAILURE;
        }

        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        foreach ($data as $row) {
            $userData = array_combine($header, $row);

            $user = new User();
            $date = \DateTime::createFromFormat('d/m/Y', $userData['dob']);
            $user->setDob($date);
            $user->setMerchid(0);
            $user->setUserNR((int) $userData['id']);

            $check = $this->userRepository->findOneBy([
                'userNR' => (int) $userData['id'],
                'dob' => $date,
            ]);
            if ($check) {
                $output->writeln('User with id '.$userData['id'].' already exists, skipping...');
                continue;
            }

            $this->em->persist($user);
        }

        $this->em->flush();

        $output->writeln('Users imported successfully!');
        $output->writeln('');

        return Command::SUCCESS;
    }
}
