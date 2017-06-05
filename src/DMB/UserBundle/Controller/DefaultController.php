<?php

namespace DMB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DMBUserBundle:Default:index.html.twig');
    }
}
