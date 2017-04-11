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

    public function postAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('DMBBlogBundle:Post')->find($id);
        if (null === $post) {
            throw new NotFoundHttpException("Le chapitre avec l'id " . $id . " n'a pas encore été rédigé.");
        }

        return $this->render('DMBBlogBundle:Default:post.html.twig', compact('post'));


    }
}
