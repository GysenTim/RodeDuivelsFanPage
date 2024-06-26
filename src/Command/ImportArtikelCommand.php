<?php

namespace App\Command;

use App\Entity\Artikel;
use App\Repository\ArtikelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-artikel',
    description: 'Import articles from a CSV file')]
class ImportArtikelCommand extends Command
{
    private $artikelRepository;
    private $em;

    public function __construct(
        EntityManagerInterface $em,
        ArtikelRepository $artikelRepository
    ) {
        $this->artikelRepository = $artikelRepository;
        $this->em = $em;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::REQUIRED, 'The path to the CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Importing articles...');
        $path = $input->getArgument('path');
        if (!file_exists($path) || !is_readable($path)) {
            $output->writeln('file not found or not readable');

            return Command::FAILURE;
        }

        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);
        foreach ($data as $row) {
            $artikelData = array_combine($header, $row);

            $artikel = new Artikel();
            $artikel->setName($artikelData['name']);
            $artikel->setDescription($artikelData['description']);
            $artikel->setPrice((int) $artikelData['price']);
            $artikel->setStock((int) $artikelData['stock']);
            $artikel->setImage($artikelData['image']);

            $check = $this->artikelRepository->findOneBy(['name' => $artikelData['name']]);
            if ($check) {
                $output->writeln('Article '.$artikelData['name'].' already exists, skipping...');
                continue;
            }
            $this->em->persist($artikel);
        }
        $this->em->flush();

        $output->writeln('Articles imported successfully!');
        $output->writeln('');

        return Command::SUCCESS;
    }
}
