<?php

namespace DMB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DMBBlogBundle:Default:index.html.twig');
    }
}
