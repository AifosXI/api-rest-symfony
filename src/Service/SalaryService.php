<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SalaryService
{
    private HttpClientInterface $client;

    public float $salary;

    public float $salaryCdi;
    public float $salaryCdd;
    public float $salaryStage;
    public float $salaryApprentis;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    public function getTotalSalary($salary){
        $this->salary = $salary;
        $this->getSalaryCdi();
        $this->getSalaryCdd();
        $this->getSalaryStage();
        $this->getSalaryApprentis();

        $totalSalary = [
            'cdi' => $this->salaryCdi,
            'cdd' => $this->salaryCdd,
            'stage' => $this->salaryStage,
            'apprentis' => $this->salaryApprentis
            ];

        return $totalSalary;

    }

    private function getSalaryCdi(){
        $salaryJson = $this->client->request(
            'POST',
            'https://mon-entreprise.urssaf.fr/api/v1/evaluate',
            [
                'json' => [
                    'situation' => [
                        'salarié . contrat . salaire brut' => [
                            "valeur" => $this->salary,
                            'unité' => '€ / mois'
                        ],
                        'salarié . contrat' => "'CDI'",
                    ],
                    'expressions' => [
                        'salarié . rémunération . net . à payer avant impôt',
                    ]
                ]
            ]
        );

        $salaryDecode = json_decode($salaryJson->getContent(), true);
        $salaryTwig = $salaryDecode['evaluate'][0]['nodeValue'];

        return $this->salaryCdi = $salaryTwig;
    }

    private function getSalaryStage(){
        $salaryJson = $this->client->request(
            'POST',
            'https://mon-entreprise.urssaf.fr/api/v1/evaluate',
            [
                'json' => [
                    'situation' => [
                        'salarié . contrat . salaire brut' => [
                            "valeur" => $this->salary,
                            'unité' => '€ / mois'
                        ],
                        'salarié . contrat' => "'stage'",
                    ],
                    'expressions' => [
                        "salarié . contrat . stage . gratification minimale",
                    ]
                ]
            ]
        );

        $salaryDecode = json_decode($salaryJson->getContent(), true);
        $salaryTwig = $salaryDecode['evaluate'][0]['nodeValue'];

        return $this->salaryStage = $salaryTwig;
    }

    private function getSalaryApprentis(){
        $salaryJson = $this->client->request(
            'POST',
            'https://mon-entreprise.urssaf.fr/api/v1/evaluate',
            [
                'json' => [
                    'situation' => [
                        'salarié . contrat . salaire brut' => [
                            "valeur" => $this->salary,
                            'unité' => '€ / mois'
                        ],
                        'salarié . contrat' => "'apprentissage'",
                    ],
                    'expressions' => [
                        "salarié . rémunération . net . à payer avant impôt",
                        "salarié . cotisations",
                        "salarié . coût total employeur"
                    ]
                ]
            ]
        );

        $salaryDecode = json_decode($salaryJson->getContent(), true);
        $salaryTwig = $salaryDecode['evaluate'][0]['nodeValue'];

        return $this->salaryApprentis = $salaryTwig;
    }

    private function getSalaryCdd(){
        $salaryJson = $this->client->request(
            'POST',
            'https://mon-entreprise.urssaf.fr/api/v1/evaluate',
            [
                'json' => [
                    'situation' => [
                        'salarié . contrat . salaire brut' => [
                            "valeur" => $this->salary,
                            'unité' => '€ / mois'
                        ],
                        'salarié . contrat' => "'CDD'",
                    ],
                    'expressions' => [
                        "salarié . rémunération . indemnités CDD . fin de contrat",
                    ]
                ]
            ]
        );

        $salaryDecode = json_decode($salaryJson->getContent(), true);
        $salaryTwig = $salaryDecode['evaluate'][0]['nodeValue'];

        return $this->salaryCdd = $salaryTwig;
    }





}