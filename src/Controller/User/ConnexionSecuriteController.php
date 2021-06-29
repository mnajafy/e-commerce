<?php

namespace App\Controller\User;

use App\Form\User\ChangePasswordType;
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
class ConnexionSecuriteController extends AbstractController
{
    /**
     * @Route("/profile/connexion-et-securite-edit-username", methods="GET|POST", name="profile_connexion_securite_edit_username")
     */
    public function profileConnexionSecuriteEditUsername(Request $request): Response
    {
        $form = $this->createFormBuilder($this->getUser())->add('username')->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'user update success!');

            return $this->redirectToRoute('profile_connexion_securite');
        }

        $msg = 'Si vous voulez changer le nom associé à votre compte client e-commerce, vous pouvez le faire ci-dessous. N\'oubliez pas de cliquer sur le bouton Enregistrer les modifications quand vous avez terminé.';
        $title = 'Changez votre nom';

        return $this->render('user/connexionSecurite/edit.html.twig', [
            'title' => $title,
            'msg' => $msg,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/connexion-et-securite-edit-email", methods="GET|POST", name="profile_connexion_securite_edit_email")
     */
    public function profileConnexionSecuriteEditemail(Request $request): Response
    {
        $form = $this->createFormBuilder($this->getUser())->add('email')->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Email update success!');

            return $this->redirectToRoute('profile_connexion_securite');
        }

        $msg = 'Si vous voulez changer le nom associé à votre compte client e-commerce, vous pouvez le faire ci-dessous. N\'oubliez pas de cliquer sur le bouton Enregistrer les modifications quand vous avez terminé.';
        $title = 'Changez votre adress email';

        return $this->render('user/connexionSecurite/edit.html.twig', [
            'title' => $title,
            'msg' => $msg,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/connexion-et-securite-edit-password", methods="GET|POST", name="profile_connexion_securite_edit_password")
     */
    public function profileConnexionSecuriteEditPassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $form->get('newPassword')->getData()));

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Password update success!');

            return $this->redirectToRoute('security_logout');
        }
        $msg = 'Si vous voulez changer le nom associé à votre compte client e-commerce, vous pouvez le faire ci-dessous. N\'oubliez pas de cliquer sur le bouton Enregistrer les modifications quand vous avez terminé.';
        $title = 'Changez votre mot de passe';
        return $this->render('user/connexionSecurite/edit.html.twig', [
            'title' => $title,
            'form' => $form->createView(),
            'msg' => $msg
        ]);
    }
}
