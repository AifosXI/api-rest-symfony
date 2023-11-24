<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiProtegee extends AbstractController {

    #[Route('/auth', name: 'login_form')]
    public function index(): Response
    {
        return $this->render('auth/index.html.twig');
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(Request $request): Response
    {
        $username = 'admin';
        $password = 'admin123';

        var_dump($request->get('username'));
        var_dump($request->get('password'));
        $usernameRequest = $request->get('username');
        $passwordRequest = $request->get('password');

        $postData = ['username' => $usernameRequest, 'password' => $passwordRequest];

        $authToken = base64_encode($username . ':' . $password);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'localhost:8000/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $authToken, 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password );
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($ch);

        curl_close($ch);

//        if(!isset($_SERVER['PHP_AUTH_USER'])) {
//            var_dump('kc');
//        } else {
//            echo 'Hello ! ' . $_SERVER['PHP_AUTH_USER'];
//            echo 'PWD ! ' . $_SERVER['PHP_AUTH_PW'];
//        }
        var_dump($_SERVER['PHP_AUTH_USER']);

        var_dump($response);
    }
}