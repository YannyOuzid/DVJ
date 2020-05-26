<?php

namespace App\Controller;

use App\Entity\Biographie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BiographieController extends AbstractController
{
    /**
     * @Route("/biographie", name="biographie")
     */
    public function index()
    {
        $pdo = $this->getDoctrine()->getManager(); //Connexion à la base de données

        $biographies = $pdo->getRepository(Biographie::class)->findAll(); //Récupération de toutes les informations dans la table Biographie

        return $this->render('biographie/index.html.twig', [
            'biographies' => $biographies,
        ]);
    }
}
