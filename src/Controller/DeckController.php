<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Form\DeckType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/deck")
 */
class DeckController extends AbstractController implements TokenAuthenticatedController
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

        $decks = $em->getRepository(Deck::class)->findBy([
            "user" => $user
        ]);

        $json = $serializer->serialize($decks, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']]);

        return new Response($json);
    }

    /**
     * @Route("", methods="POST")
     * @param Request $req
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function create(Request $req, SerializerInterface $serializer): Response
    {
        $user = $req->get("user");
        $em = $this->getDoctrine()->getManager();

        $deck = new Deck();
        $deck->setUser($user);
        $form = $this->createForm(DeckType::class, $deck);
        $form->submit($req->toArray());

        if (!$form->isValid()) return new JsonResponse(
            ['error' => 'Form was not validated'],
            Response::HTTP_BAD_REQUEST
        );

        $em->persist($deck);
        $em->flush();

        $json = $serializer->serialize($deck, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']]);
        return new Response($json);
    }

    /**
     * @Route("/{id}", methods="GET")
     * @param Request $req
     * @param SerializerInterface $serializer
     * @param $id
     * @return Response
     */
    public function show(Request $req, SerializerInterface $serializer, $id): Response
    {
        $user = $req->get("user");
        $em = $this->getDoctrine()->getManager();

        $deck = $em->getRepository(Deck::class)->find($id);

        if (!$deck) return new JsonResponse(
            ['error' => 'Deck not found'],
            Response::HTTP_NOT_FOUND
        );

        if ($deck->getUser() !== $user) return new JsonResponse(
            ['error' => 'User is not allowed to access this deck'],
            Response::HTTP_FORBIDDEN
        );

        $json = $serializer->serialize($deck, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']]);
        return new Response($json);
    }

    /**
     * @Route("/{id}", methods="PUT")
     * @param Request $req
     * @param SerializerInterface $serializer
     * @param $id
     * @return Response
     */
    public function update(Request $req, SerializerInterface $serializer, $id): Response
    {
        $user = $req->get("user");
        $em = $this->getDoctrine()->getManager();

        $deck = $em->getRepository(Deck::class)->find($id);

        if (!$deck) return new JsonResponse(
            ['error' => 'Deck not found'],
            Response::HTTP_NOT_FOUND
        );

        if ($deck->getUser() !== $user) return new JsonResponse(
            ['error' => 'User is not allowed to edit this deck'],
            Response::HTTP_FORBIDDEN
        );

        $form = $this->createForm(DeckType::class, $deck);
        $form->submit($req->toArray());

        if (!$form->isValid()) return new JsonResponse(
            ['error' => 'Form was not validated'],
            Response::HTTP_BAD_REQUEST
        );

        $em->persist($deck);
        $em->flush();

        $json = $serializer->serialize($deck, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']]);
        return new Response($json);
    }

    /**
     * @Route("/{id}", methods="DELETE")
     * @param Request $req
     * @param $id
     * @return Response
     */
    public function delete(Request $req, $id): Response
    {
        $user = $req->get("user");
        $em = $this->getDoctrine()->getManager();

        $deck = $em->getRepository(Deck::class)->find($id);

        if (!$deck) return new JsonResponse(
            ['error' => 'Deck not found'],
            Response::HTTP_NOT_FOUND
        );

        if ($deck->getUser() !== $user) return new JsonResponse(
            ['error' => 'User is not allowed to edit this deck'],
            Response::HTTP_FORBIDDEN
        );

        $em->remove($deck);
        $em->flush();

        return new JsonResponse(
            ['success' => 'Deck was deleted']
        );
    }
}
