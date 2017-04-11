<?php

namespace DMB\BlogBundle\Controller;

use DMB\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('DMBBlogBundle:Post')->findAll();

        return $this->render('DMBBlogBundle:Default:index.html.twig', compact('posts'));
    }
}
