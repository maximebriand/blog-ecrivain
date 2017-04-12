<?php

namespace DMB\BlogBundle\Controller;

use DMB\BlogBundle\Entity\Comment;
use DMB\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        $comment = new Comment;
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $comment);

        $formBuilder
            ->add('date',      DateType::class)
            ->add('content',   TextareaType::class)
            ->add('author',    TextType::class)
            ->add('email',     TextType::class)
            ->add('save',      SubmitType::class, array(
                'attr' => array('class' => 'col s2 btn waves-effect waves-light')
            ))
        ;

        $form = $formBuilder->getForm();

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('DMBBlogBundle:Post')->find($id);
        if (null === $post) {
            throw new NotFoundHttpException("Le chapitre avec l'id " . $id . " n'a pas encore été rédigé.");
        }

        $comments = $em
            ->getRepository('DMBBlogBundle:Comment')
            ->findBy(array('post' => $id))
        ;

        return $this->render('DMBBlogBundle:Default:post.html.twig', array(
            'form' => $form->createView(),
            'post' => $post,
            'comments' => $comments,
        ));

    }
}
