<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UtilityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */

class LoginController extends AbstractController
{
    /**
     * @Route("/signup", methods="POST")
     * @param Request $req
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function signup(Request $req, EntityManagerInterface $em)
    {
        // Get the request elements
        $email = $req->get('email');
        $password = $req->get('password');
        $languages = ['en', 'fr'];

        // Check that the email is not already used
        $user = $em->getRepository(User::class)->findBy(['email' => $email]);
        if ($user) return new JsonResponse(
            ['error' => 'User with email ' . $email . ' already exists'],
            400
        );

        // Create the new user
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setLanguages($languages);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['success' => 'User was successfully created.']);
    }

    /**
     * @Route("/login", methods="POST")
     * @param Request $req
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function login(Request $req, EntityManagerInterface $em)
    {
        $data = $req->toArray();

        if (!isset($data['email']) || !$data['email'] || !isset($data['password']) || !$data['password']) {
            return new JsonResponse(["error" => "Missing data"], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy([
            'email' => $data['email']
        ]);

        if (!$user) {
            return new JsonResponse(["error" => "User not found"], 400);
        }
        if (!password_verify($data['password'], $user->getPassword())) {
            return new JsonResponse(["error" => "Password incorrect"], 400);
        }

        // Create a JWT
        $jwt = UtilityService::generateJWT($user);
        $em->flush();

        return new JsonResponse(['authKey' => $jwt]);
    }
}
