<?php

namespace App\Command;

use App\Entity\SoftwareVersion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-software',
    description: 'Add a short description for your command',
)]
class ImportSoftwareCommand extends Command
{
      protected static $defaultName = 'app:import-software';

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
            parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
                $this->setDescription('Import software versions from JSON file into database.');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    //   $filePath = __DIR__ . '/../../../../assets/softwareversions.json';
        $filePath = $this->getApplication()->getKernel()->getProjectDir() . '/assets/softwareversions.json';

        if (!file_exists($filePath)) {
            $output->writeln('<error>JSON file not found!</error>');
            return Command::FAILURE;
        }

        $jsonContent = file_get_contents($filePath);
        $softwareList = json_decode($jsonContent, true);

        foreach ($softwareList as $data) {
            $software = new SoftwareVersion();
            $software->setName($data['name']);
            $software->setSystemVersionAlt($data['system_version_alt']);
            $software->setSystemVersion($data['system_version']);
            $software->setLink($data['link']);
            $software->setStLink($data['st'] ?? '');
            $software->setGdLink($data['gd'] ?? '');
            $software->setLatest($data['latest'] ?? false);

            $this->em->persist($software);
        }

        $this->em->flush();

        $output->writeln('<info>Software versions imported successfully!</info>');

        return Command::SUCCESS;
    }
}
