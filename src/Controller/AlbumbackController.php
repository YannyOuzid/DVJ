<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumbackController extends AbstractController
{
    /**
     * @Route("/albumback", name="albumback")
     */
    public function index(Request $request)
    {
         //Connexion a la base de données

         $pdo = $this->getDoctrine()->getManager();

         $album = new Album();
 
         //Création du formulaire d'ajout
 
         $form = $this->createForm(AlbumType::class, $album);
 
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
 
             $fichier = $form->get('photoUpload')->getData();
 
             if($fichier){
                 $nomFichier = uniqid() . '.' . $fichier->guessExtension();
 
                 try{
                     //On essaie de deplacer le fichier
                     $fichier->move(
                     $this->getParameter('upload_dir'),
                     $nomFichier
                     );
                 }
                 catch(FileException $e){
                     $this->addFlash('danger', "Impossible d'uploder le fichier");
                     return $this->redirecttoRoute('error');
 
                 }
 
                 $album->setPhoto($nomFichier);
             }
             $pdo->persist($album);
             $pdo->flush();
         }
        
         $albums = $pdo->getRepository(Album::class)->findAll();
        return $this->render('albumback/index.html.twig', [
            'albums' => $albums,
            'form_ajout' => $form->createView(),
        ]);
    }

    /**
     * @Route("albumback/edit/{id}", name="albumback_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Album $album): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

        }

        return $this->render('albumback/albumback.html.twig', [
            'album' => $album,
            'form_edit' => $form->createView(),
        ]);
    }

    /**
     * @Route ("albumback/delete/{id}", name="albumback_delete")
     */

    public function delete(Album $album=null){

        //Suppression des produits

        if($album !=null){

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($album);
            $album->supprphoto();
            $pdo->flush();
        }
        return $this->redirectToRoute('albumback');
    }

}
