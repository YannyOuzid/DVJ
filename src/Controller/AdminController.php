<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();
        $commentaires = $em->getRepository(Commentaire::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'commentaires' => $commentaires
        ]);
    }

    /**
     * @Route("/editRole/{id}", name="editRole")
     */
    public function editRole(User $user = null)
    {
        if($user == null){
            $this->addFlash('error', 'Utilisateur introuvable');
            return $this->redirectToRoute('athlete');

        }

        if($user->hasRole('ROLE_ADMIN') ){
            $user->setRoles(['Role_USER']);
        }
        else{
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Rôle modifié');
        return $this->redirectToRoute('admin');
    }


}

