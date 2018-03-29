<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function _invoke()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findAll();

        return $this->render('homepage/index.html.twig', compact('users'));
    }
}
