<?php

namespace App\Command;

use App\Entity\Card;
use App\Entity\Edition;
use App\Entity\Face;
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
            $this->updateCard($card, $c, $set);
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

    private function updateCard($card, $data, $set) {
        $card->setEdition($set);
        $card->setName($data['name']);
        $card->setCollectorNumber($data['collector_number']);
        $card->setCmc($data['cmc']);
        $card->setColorIdentity($data['color_identity']);
        $card->setRarity($data['rarity']);
        $card->setReleasedAt(new \DateTime($data['released_at']));
        $card->setIsReprint($data['reprint']);
        $card->setLayout($data['layout']);
        $card->setBorderColor($data['border_color']);
        if (isset($data['prices']['usd'])) $card->setPriceUsd($data['prices']['usd']);
        if (isset($data['prices']['eur'])) $card->setPriceEur($data['prices']['eur']);

        $this->em->persist($card);

        $this->updateCardFaces($card, $data);

        return $card;
    }

    private function updateCardFaces(Card $card, $data) {
        $cardFaces = $card->getFaces();

        // Multiple faces
        if(isset($data['card_faces'])) {
            foreach ($data['card_faces'] as $i => $cf) {
                // Find or create the face
                $face = null;
                foreach ($cardFaces as $cardFace) {
                    if($cardFace->getFaceIndex() === $i) $face = $cardFace;
                }
                if(!$face) $face = new Face();

                $face->setCard($card);
                $face->setFaceIndex($i);
                $this->updateFace($face, $cf);
            }
        }
        // One face
        else {
            // Find or create the face
            $face = null;
            if(sizeof($cardFaces)) $face = $cardFaces[0];
            if(!$face) $face = new Face();

            $face->setCard($card);
            $face->setFaceIndex(0);
            $this->updateFace($face, $data);
        }
    }

    private function updateFace($face, $data) {

        $face->setName($data['name']);
        if(isset($data['mana_cost'])) $face->setManaCost($data['mana_cost']);
        $face->setTypeLine($data['type_line']);
        if(isset($data['oracle_text'])) $face->setOracleText($data['oracle_text']);
        if(isset($data['flavor_text'])) $face->setFlavorText($data['flavor_text']);
        if(isset($data['power'])) $face->setPower($data['power']);
        if(isset($data['toughness'])) $face->setToughness($data['toughness']);
        if(isset($data['loyalty'])) $face->setLoyalty($data['loyalty']);
        if(isset($data['artist'])) $face->setArtist($data['artist']);
        if(isset($data['watermark'])) $face->setWatermark($data['watermark']);
        if(isset($data['image_uris']['normal'])) $face->setImageUriNormal($data['image_uris']['normal']);
        if(isset($data['image_uris']['png'])) $face->setImageUriPng($data['image_uris']['png']);

        $this->em->persist($face);
    }
}
