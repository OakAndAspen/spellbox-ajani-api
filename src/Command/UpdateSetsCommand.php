<?php

namespace App\Command;

use App\Entity\Edition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateSetsCommand extends Command
{
    protected static $defaultName = 'app:update-sets';
    private $em;
    private $setsData = null;
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
        $output->writeln("Fetching the sets data...");
        $this->getSetsData();
        $this->updateSets($output);
        return Command::SUCCESS;
    }

    private function getSetsData()
    {
        // Fetching the data from Scryfall API
        $data = file_get_contents(self::SCRYFALL_SETS_INDEX_URL);
        $array = json_decode($data, true);
        $this->setsData = $array["data"];
    }

    private function updateSets($output)
    {
        // Getting the existing sets
        $existingSets = $this->em->getRepository(Edition::class)->findAll();

        // Preparing the console output
        $setsCount = sizeof($this->setsData);
        $progressSection = $output->section();
        $setSection = $output->section();
        $progressSection->overwrite('Updating the sets...');

        foreach ($this->setsData as $i => $s) {
            // Updating the console output
            $name = $s['name'];
            $percent = round($i / $setsCount * 100);
            $progressSection->overwrite('Updating the sets ('.$percent.'%)');
            $setSection->overwrite('Updating ' . $name);

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
        $setSection->overwrite('Saving the sets in the database...');
        $this->em->flush();
        $setSection->overwrite('All sets were updated!');
    }
}
