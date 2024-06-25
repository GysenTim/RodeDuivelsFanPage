<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Entity\User;
use DateTimeInterface;
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
    public function __construct(EntityManagerInterface $em,UserRepository $userRepository)
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
        if(($fp = fopen($path, 'r')) !== false)
        {
            while(($row = fgetcsv($fp, 1000, ',')) !== false){
                $user = new User();
                $date = \DateTime::createFromFormat('d/m/Y', $row[1]);
                // the first column is the id, we need to parse it to an integer
                $id = $row[0];
                if((int) $id == 0){
                    continue;
                }
                // make sure the user does not already exist
                $check = $this->userRepository->findOneBy(['userNR' => (int) $id]);
                if($check !== null){
                    $output->writeln('User with id ' . $id . ' already exists, skipping...');
                    continue;
                }
                $user->setId((int)$row[0]);
                $user->setDob($date);
                $user->setMerchid(0);
                $user->setUserNR((int)$row[0]);
                

                $this->em->persist($user);

            }
        }

        $this->em->flush();


        return Command::SUCCESS;
    }
}