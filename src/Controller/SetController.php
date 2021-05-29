<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\Edition;
use App\Form\DeckType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/set")
 */
class SetController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("", methods="GET")
     * @param Request $req
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $req, SerializerInterface $serializer): Response
    {
        $user = $req->get("user");
        $em = $this->getDoctrine()->getManager();

        $sets = $em->getRepository(Edition::class)->findAll();

        $json = $serializer->serialize($sets, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['cards']]);

        return new Response($json);
    }

    /**
     * @Route("/{code}", methods="GET")
     * @param Request $req
     * @param SerializerInterface $serializer
     * @param $code
     * @return Response
     */
    public function show(Request $req, SerializerInterface $serializer, $code): Response
    {
        $user = $req->get("user");
        $em = $this->getDoctrine()->getManager();

        $set = $em->getRepository(Edition::class)->findOneBy(['code' => $code]);

        if (!$set) return new JsonResponse(
            ['error' => 'Set not found'],
            Response::HTTP_NOT_FOUND
        );

        $json = $serializer->serialize($set, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']]);
        return new Response($json);
    }
}
