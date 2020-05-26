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

        $user = $this->get('security.token_storage')->getToken()->getUser(); // Récupération du token de l'utilisateur

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
}
