<?php

namespace App\Controller\User;

use App\Form\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Controller used to manage current user.
 *
 * @IsGranted("ROLE_USER")
 * 
 * @author Mohammad Najafy <m.najafy@hotmail.com>
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profile/{username}/{id}", name="profile")
     */
    public function profile(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/profile.html.twig', []);
    }

    /**
     * @Route("/profile/adresse", methods="GET|POST", name="profile_adresse")
     */
    public function profileAdresse(): Response
    {
        return $this->render('user/adresse.html.twig', []);
    }

    /**
     * @Route("/profile/connexion-et-securite", methods="GET|POST", name="profile_connexion_securite")
     */
    public function profileConnexionSecurite(): Response
    {
        return $this->render('user/connexionSecurite/index.html.twig', []);
    }
}
