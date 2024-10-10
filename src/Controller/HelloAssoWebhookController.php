<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Entity\Vente;
use App\Model\TypeBeneficiaire;
use App\Repository\BeneficiaireRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Config\Doctrine\Dbal\ConnectionConfig\ReplicaConfig;

class HelloAssoWebhookController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function homeAction(): Response
    {
        return $this->render('index.html.twig');
    }


    #[Route('/webhook/helloasso/{authToken}', name: 'app_hello_asso_webhook', methods: [Request::METHOD_POST])]
    public function webhookAction(
        Request $request,
        string $authToken,
        ParameterBagInterface $parameters,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        BeneficiaireRepository $beneficiaires
    ): Response {
        // Valider le secret de la requête
        if ($authToken !== $parameters->get('webhook_autentication_token')) {
            return new Response('Token d\'authentification incorrect', Response::HTTP_UNAUTHORIZED);
        }

        // Récupérer le corps de la requête
        $content = $request->getContent();

        // Décoder le JSON en tableau PHP
        $payload = json_decode($content, true);

        // Vérifier si le décodage a réussi
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new Response('Erreur de décodage JSON', Response::HTTP_BAD_REQUEST);
        }

        // Valide le payload
        if (!$payload['eventType'] == 'Payment') {
            return new Response('Payload non analysable', Response::HTTP_NO_CONTENT);
        }

        $vente = new Vente();
        $vente->setDatePaiement(new DateTime($payload['data']['date']));

        $nom = $payload['data']['payer']['firstName'] . ' ' . $payload['data']['payer']['lastName'];
        $vente->setBeneficiaire($beneficiaires->findOneByNom($nom) ?: new Beneficiaire($nom, TypeBeneficiaire::ADHERENT));

        $vente->setMontantTotal(((int) $payload['data']['amount']) / 100);


        $entityManager->persist($vente);
        $entityManager->flush();

        return new Response('Vente créée', Response::HTTP_CREATED);
    }
}
