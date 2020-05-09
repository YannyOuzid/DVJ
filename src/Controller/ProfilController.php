<?php

namespace App\Controller;

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
        $pdo = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($user);
            $pdo->flush();
        }

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'form_edit' => $form->createView(),
        ]);
    }
}
