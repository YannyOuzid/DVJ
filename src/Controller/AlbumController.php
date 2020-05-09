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
        $pdo = $this->getDoctrine()->getManager();

        $albums = $pdo->getRepository(Album::class)->findAll();


        return $this->render('album/index.html.twig', [
            'albums' => $albums,
            
        ]);
    }

     /**
     * @Route("album/show/{id}", name="albumback_show")
     */
    public function show(Album $album, Request $request)
    {
        $commentaire = new Commentaire();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $commentaire->setUtilisateur($user);
        $commentaire->setDate(new \DateTime('now'));
        $commentaire->setAlbum($album);

        $form_comment = $this->createForm(CommentaireType::class, $commentaire);
        $form_comment->handleRequest($request);

        if ($form_comment->isSubmitted() && $form_comment->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

        }

        $pdo = $this->getDoctrine()->getManager();


        $commentaires = $pdo->getRepository(Commentaire::class)->findBy(
            array('album' => $album));
       

        return $this->render('album/album.html.twig', [
            'album' => $album,
            'form_comment' => $form_comment->createView(),
            'commentaires' => $commentaires
        ]);
    }
}
