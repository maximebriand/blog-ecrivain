<?php
/**
 * Created by PhpStorm.
 * User: maximebriand
 * Date: 15/04/2017
 * Time: 16:01
 */

namespace DMB\UserBundle\Controller;

use \Symfony\Component\BrowserKit\Request;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SecurityController extends Controller
{
    public function loginAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBER')){
            return $this->redirectToRoute('dmb_blog_homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('DMBUserBundle:Security:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
}