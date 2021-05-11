<?php

namespace App\Command;

use App\Entity\Card;
use App\Entity\Edition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCardsCommand extends Command
{
    protected static $defaultName = 'app:update-cards';
    private $em;
    private $setsData = null;
    const SCRYFALL_SETS_INDEX_URL = 'https://api.scryfall.com/sets';

    protected function configure(): void
    {
        $this
            ->setDescription('Updates the cards')
            ->setHelp('Updates the cards with the data from Scryfall API');
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

        // Getting the existing sets
        $existingSets = $this->em->getRepository(Edition::class)->findAll();

        // Preparing the console output
        $setsCount = sizeof($this->setsData);
        $progressSection = $output->section();
        $setSection = $output->section();
        $cardSection = $output->section();
        $progressSection->overwrite('Updating the cards...');

        foreach ($this->setsData as $i => $s) {
            // Updating the console output
            $name = $s['name'];
            $percent = round($i / $setsCount * 100);
            $progressSection->overwrite('Updating the cards (' . $percent . '%)');
            $setSection->overwrite('Updating cards from ' . $name);

            // Finding the existing set
            $set = null;
            foreach ($existingSets as $es) {
                if ($es->getCode() === $s["code"]) $set = $es;
            }
            if ($set === null) return Command::FAILURE;

            // Updating the set's cards
            $this->updateSetCards($cardSection, $set, $s['search_uri']);
        }
        $output->writeln('Saving the cards in the database...');
        $this->em->flush();
        $output->writeln('All cards were updated!');
        return Command::SUCCESS;
    }

    private function getSetsData()
    {
        // Fetching the data from Scryfall API
        $data = file_get_contents(self::SCRYFALL_SETS_INDEX_URL);
        $array = json_decode($data, true);
        $this->setsData = $array["data"];
    }

    private function updateSetCards($cardSection, $set, $searchUri)
    {
        $cardSection->overwrite('Fetching the cards data...');
        $cardsData = $this->getCardsData($searchUri);
        $existingCards = $this->em->getRepository(Card::class)->findBy(["edition" => $set]);

        foreach ($cardsData as $i => $c) {

            // Updating the console output
            $cn = $c['collector_number'];
            $percent = round($i / sizeof($cardsData) * 100);
            $cardSection->overwrite($percent . ' % - Updating card n°' . $cn);

            // Check if the card already exists
            $card = null;
            foreach ($existingCards as $ec) {
                if ($ec->getCollectorNumber() === $c['collector_number']) {
                    $card = $ec;
                }
            }
            if ($card === null) $card = new Card();

            // Update the card's data
            $card->setEdition($set);
            $card->setName($c['name']);
            $card->setCollectorNumber($c['collector_number']);
            $card->setCmc($c['cmc']);
            $card->setColorIdentity($c['color_identity']);
            $card->setRarity($c['rarity']);
            $card->setReleasedAt(new \DateTime($c['released_at']));
            $card->setIsReprint($c['reprint']);
            $card->setLayout($c['layout']);
            $card->setBorderColor($c['border_color']);
            if (isset($c['prices']['usd'])) $card->setPriceUsd($c['prices']['usd']);
            if (isset($c['prices']['eur'])) $card->setPriceEur($c['prices']['eur']);

            $this->em->persist($card);
        }

        $cardSection->overwrite('Saving the cards in the database...');
        $this->em->flush();
    }

    private function getCardsData($url)
    {
        $cards = [];
        $hasMore = true;

        // Fetching the data from Scryfall API
        while ($hasMore) {
            $data = null;
            try {
                $data = file_get_contents($url);
            } catch (\Exception $e) {
                echo "Couldn't get cards from this url: " . $url;
            }
            if(!$data) break;

            $array = json_decode($data, true);
            $cards = array_merge($cards, $array["data"]);

            if ($array['has_more']) $url = $array['next_page'];
            else $hasMore = false;
            usleep(50000);
        }

        return $cards;
    }
}
