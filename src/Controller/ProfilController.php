<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager(); // Connexion à la base de donnée

        $user = $this->getUser(); // Récupération de l'utilisateur

        $form = $this->createForm(UserType::class, $user); // Création du formulaire pour l'utilisateur

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $pdo = $this->getDoctrine()->getManager();

            $pdo->persist($user); // Modification des informations

            $pdo->flush();
        }

        $commentaires = $pdo->getRepository(Commentaire::class)->findBy(
            array('utilisateur' => $user)); // Récupérations des informations de la colonne utilisateur dans la table User en fonction du Token

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'form_edit' => $form->createView(),
            'commentaires' => $commentaires,
        ]);
    }

     /**
     * @Route ("commentaire/delete/{id}", name="commentaire_delete")
     */

    public function delete(Commentaire $commentaire=null){

        //Suppression des produits

        if($commentaire !=null){

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($commentaire); //Suppression des données
            $pdo->flush();
        }
        return $this->redirectToRoute('profil');
    }
}
