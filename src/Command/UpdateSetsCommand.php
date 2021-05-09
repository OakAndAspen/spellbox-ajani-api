<?php

namespace App\Command;

use App\Entity\Edition;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateSetsCommand extends Command
{
    protected static $defaultName = 'app:update-sets';
    private $em;
    const SCRYFALL_SETS_INDEX_URL = 'https://api.scryfall.com/sets';

    protected function configure(): void
    {
        $this
            ->setDescription('Updates the sets')
            ->setHelp('Updates the sets with the data from Scryfall API');
    }

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getData($output);
        return Command::SUCCESS;
    }

    private function getData($output)
    {
        // Fetching the data from Scryfall API
        $data = file_get_contents(self::SCRYFALL_SETS_INDEX_URL);
        $array = json_decode($data, true);
        $sets = $array["data"];

        // Getting the existing sets
        $existingSets = $this->em->getRepository(Edition::class)->findAll();

        // Preparing the console output
        $setsCount = sizeof($sets);
        $section = $output->section();
        $section->writeln('Updating sets...');

        foreach ($sets as $i => $s) {
            // Updating the console output
            $name = $s['name'];
            $percent = round($i / $setsCount * 100);
            $section->overwrite('Updating sets (' . $percent . ' %) - ' . $name);

            // Checking if the set already exists in the database
            $set = null;
            foreach ($existingSets as $es) {
                if ($es->getCode() === $s["code"]) $set = $es;
            }
            if ($set === null) $set = new Edition();

            // Updating the set's data
            $set->setName($s["name"]);
            $set->setCode($s["code"]);
            $set->setSetType($s["set_type"]);
            if (isset($s["released_at"])) $set->setReleasedAt(new \DateTime($s["released_at"]));
            $set->setIconSvgUri($s["icon_svg_uri"]);
            if (isset($s["block"])) $set->setBlock($s["block"]);
            if (isset($s["block_code"])) $set->setBlockCode($s["block_code"]);
            $set->setLanguages(['en', 'fr']);

            $this->em->persist($set);
        }
        $this->em->flush();
        $section->overwrite('All sets were updated!');
    }
}
