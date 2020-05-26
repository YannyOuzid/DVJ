<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    /**
     * @Route("/album", name="album")
     */
    public function index()
    {
        $pdo = $this->getDoctrine()->getManager(); //Connexion à la base de données

        $albums = $pdo->getRepository(Album::class)->findAll(); // Récupérations de toutes les données dans la table Album


        return $this->render('album/index.html.twig', [
            'albums' => $albums,
            
        ]);
    }

     /**
     * @Route("album/show/{id}", name="album_show")
     */
    public function show(Album $album, Request $request)
    {
        $commentaire = new Commentaire();

        $user = $this->getUser(); // Obtenir l'utilisateur

        $commentaire->setUtilisateur($user); // Mettre l'id de l'utilisateur dans la table des commentaires
        $commentaire->setDate(new \DateTime('now')); //Obtenir la date
        $commentaire->setAlbum($album); // Mettre l'id de l'album dans la table des commentaires

        $form_comment = $this->createForm(CommentaireType::class, $commentaire); //Création du formulaire des commentaires
        $form_comment->handleRequest($request);

        if ($form_comment->isSubmitted() && $form_comment->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire); //Création d'un commentaire
            $entityManager->flush();

        }
    

        $pdo = $this->getDoctrine()->getManager(); // Connexion à la base de données


        $commentaires = $pdo->getRepository(Commentaire::class)->findBy(
            array('album' => $album)); // Obtenir les informations de la colonne album dans la table Commentaire 
       
        return $this->render('album/album.html.twig', [
            'album' => $album,
            'form_comment' => $form_comment->createView(),
            'commentaires' => $commentaires
        ]);
    }
}
