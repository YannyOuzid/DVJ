<?php

namespace App\Controller;

use App\Entity\Biographie;
use App\Form\BiographieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BiographiebackController extends AbstractController
{
    /**
     * @Route("/biographieback", name="biographieback")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager(); // Connexion à la base de données

        $biographie = new Biographie(); 

        $form_ajout = $this->createForm(BiographieType::class, $biographie); // Création d'un form pour l'entity Biographie

        $form_ajout->handleRequest($request);

        if ($form_ajout->isSubmitted() && $form_ajout->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($biographie);
            $entityManager->flush(); // Ajout d'un nouveau paragraphe dans la biographie

        }

        $biographies = $pdo->getRepository(Biographie::class)->findAll(); // Récupération des informations dans la table Biographie

        return $this->render('biographieback/index.html.twig', [
            'form_ajout' => $form_ajout->createView(),
            'biographies' => $biographies,
        ]);
    }

    /**
     * @Route("/biographieback/edit/{id}", name="biographieback_edit")
     */
    public function edit(Request $request, Biographie $biographie)
    {

        $form_edit = $this->createForm(BiographieType::class, $biographie); // Création du form pour la biographie

        $form_edit->handleRequest($request);

        if ($form_edit->isSubmitted() && $form_edit->isValid()) {
            $this->getDoctrine()->getManager()->flush(); // Modification des informations

        }

        return $this->render('biographieback/biographieback.html.twig', [
            'form_edit' => $form_edit->createView(),
            'biographie' => $biographie,
        ]);
    }

    /**
     * @Route ("biographieback/delete/{id}", name="biographieback_delete")
     */

    public function delete(Biographie $biographie=null){

        //Suppression des produits

        if($biographie !=null){

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($biographie); // Suppression de l'information
            $pdo->flush();
        }
        return $this->redirectToRoute('biographieback'); // Retour à la page
    }
}
