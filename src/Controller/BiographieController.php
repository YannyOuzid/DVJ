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
        $pdo = $this->getDoctrine()->getManager();

        $biographies = $pdo->getRepository(Biographie::class)->findAll();

        return $this->render('biographie/index.html.twig', [
            'biographies' => $biographies,
        ]);
    }
}
