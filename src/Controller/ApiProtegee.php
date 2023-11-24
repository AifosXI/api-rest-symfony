<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        $credentials = base64_encode($username . ':' . $password);

        $headers = [
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/json',
        ];

        $httpClient = HttpClient::create();

        try {
            // Effectuer la requête POST vers le point d'authentification
            $response = $httpClient->request('POST', 'http://localhost:8000/login_check', [
                'headers' => $headers,
            ]);

            // Vérifier le statut de la réponse ou tout autre critère
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getContent(), true);

                return $this->render('auth/index.html.twig', [
                    'data' => $data,
                ]);
            } else {
                // Gérer le cas où l'authentification a échoué
                return $this->render('auth/login_failed.html.twig');
            }
        } catch (\Exception $exception) {
            // Gérer les erreurs d'exception
            dd($exception->getMessage());
            return $this->render('auth/login_error.html.twig');
        }
    }

    #[Route('/login_check', name: 'login_check', methods: 'POST')]
    public function loginCheck(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Récupérer les informations d'identification du tableau de données
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        // Vérifier les informations d'identification (exemple simple)
        if ($username === 'admin' && $password === 'admin123') {
            // Les informations d'identification sont valides
            return new JsonResponse(['success' => true]);
        } else {
            // Les informations d'identification sont invalides
            return new JsonResponse(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
    }

    #[Route('/entreprise-update/{siren}', name: 'update-company', methods: ['PATCH'])]
    public function patchCompany(Request $request) {
        if($_SERVER['REQUEST_METHOD'] === 'PATCH')
        {
            if(json_validate($request->getContent()))
            {
                $requestBody = json_decode($request->getContent());

                $fileName = 'entreprise_' . $request->get('siren') . '.json';

                if(is_file($fileName))
                {
                    $currentContent = json_decode(file_get_contents($fileName), true);

                    $content = file_get_contents($fileName);

                    foreach ($requestBody as $key => $item) {
                        foreach ($currentContent['results'][0] as $keyText => $text)
                        {
                            if($key === $keyText)
                            {
                                $content = str_replace($text, $item, $content);
                            }
                        }
                    }
                    file_put_contents($fileName, $content);
                    echo 'L\'entreprise a été modifiée';
                    http_response_code(200);
                    exit;
                } else {
                    throw new HttpException(Response::HTTP_NOT_FOUND,'Aucune entreprise avec ce SIREN', null);
                }
            } else {
                throw new HttpException(Response::HTTP_BAD_REQUEST,'Format JSON invalide', null);
            }
        } else {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED,'La méthode utilisée n\'est pas autorisée. Uniquement PATCH est autorisé', null);
        }
    }
}