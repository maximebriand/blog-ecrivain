<?php

namespace DMB\BlogBundle\Controller;

use DMB\BlogBundle\Entity\Comment;
use DMB\BlogBundle\Entity\Post;
use DMB\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

use DMB\BlogBundle\Form\CommentType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('DMBBlogBundle:Post')->findAll();

        return $this->render('DMBBlogBundle:Default:index.html.twig', compact('posts'));


    }

    public function postAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('DMBBlogBundle:Post')->find($id);
        if (null === $post) {
            throw new NotFoundHttpException("Le chapitre avec l'id " . $id . " n'a pas encore été rédigé.");
        }

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);

        // Si la requête est en POST
        if ($request->isMethod('POST')) {

            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);
            $comment
                ->setDate(new \DateTime(('now')))
                ->setPost($post)
            ;


            if ($form->isValid()) {
                $em->persist($comment);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Votre commentaire a bien été enregistré.');
                return $this->redirectToRoute('dmb_blog_post', array('id' => $id));
            }

        }

        $comments = $em
            ->getRepository('DMBBlogBundle:Comment')
            ->findBy(array('post' => $id), array('id' => 'desc'))
        ;
        return $this->render('DMBBlogBundle:Default:post.html.twig', array(
            'form' => $form->createView(),
            'post' => $post,
            'comments' => $comments,
        ));
    }
}
