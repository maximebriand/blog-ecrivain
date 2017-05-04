<?php

namespace DMB\BlogBundle\Controller;

use DMB\BlogBundle\DMBBlogBundle;
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
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //if it is an admin we display all chapters live and draft
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $posts = $em->getRepository('DMBBlogBundle:Post')->findAll();
        }
        else //we display only live chapter
        {
            $posts = $em->getRepository('DMBBlogBundle:Post')->findAllActivePosts();
        }


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $posts, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('DMBBlogBundle:Default:index.html.twig', compact('pagination'));


    }

    public function postAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('DMBBlogBundle:Post')->find($id);
        if (null === $post) {
            throw new NotFoundHttpException("Le chapitre avec l'id " . $id . " n'a pas encore été rédigé.");
        }

        //generate previous and next chapter
        $chapterNumber = $post->getChapterNumber();
        $previousChapter = $em->getRepository('DMBBlogBundle:Post')->findByIdChapterNumber($chapterNumber - 1);
        $nextChapter = $em->getRepository('DMBBlogBundle:Post')->findByIdChapterNumber($chapterNumber + 1);





        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);

        // If it is a POST request we manage to add comment
        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            $comment //content is get from the form we add the date, the chapter (post) and the active user
                ->setMember($this->getUser())
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

        //display all comments from the specific post
        $comments = $em
            ->getRepository('DMBBlogBundle:Comment')
            ->findBy(array('post' => $id), array('id' => 'desc'))
        ;
        return $this->render('DMBBlogBundle:Default:post.html.twig', array(
            'form' => $form->createView(),
            'post' => $post,
            'comments' => $comments,
            'previousChapter' => $previousChapter,
            'nextChapter' => $nextChapter,
        ));
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('DMBBlogBundle:Page')->findAll();

        return $this->render('DMBBlogBundle:Default:menu.html.twig', compact('pages'));
    }

    public function pageAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('DMBBlogBundle:Page')->find($id);
        if (null === $page) {
            throw new NotFoundHttpException("La page avec l'id " . $id . " n'existe pas.");
        }

        return $this->render('DMBBlogBundle:Default:page.html.twig', compact('page'));

    }
}
