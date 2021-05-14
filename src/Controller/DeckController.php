<?php

namespace App\Controller;

use App\Entity\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class DeckController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/api/deck", name="deck")
     * @param Request $req
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $req, SerializerInterface $serializer): Response
    {
        $user = $req->get("user");
        $em = $this->getDoctrine()->getManager();

        $decks = $em->getRepository(Deck::class)->findBy([
            "user" => $user
        ]);

        $json = $serializer->serialize($decks, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']]);

        return new Response($json);
    }
}
