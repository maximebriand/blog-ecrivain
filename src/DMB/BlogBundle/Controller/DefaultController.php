<?php

namespace DMB\BlogBundle\Controller;

use DMB\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DMB\BlogBundle\Form\CommentType;


class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        $key = md5('list posts');

        $em = $this->getDoctrine()->getManager();
        //if it is an admin we display all chapters live and draft
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $posts = $em->getRepository('DMBBlogBundle:Post')->findAllPostsOrderByChapter();
        }
        else //we display only live chapter
        {
            //cache is used only for non admin user
            $cache = $this->get('dmb_blog.checkcache');
            $doctrine = $em->getRepository('DMBBlogBundle:Post')->findAllActivePostsOrderByChapter();
            $posts = $cache->checkIfStoredInCache($key, $doctrine);

        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $posts, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('DMBBlogBundle:Default:index.html.twig', compact('pagination'));
    }

    public function postAction($id, Request $request) //must think with admin without cache
    {
        $em = $this->getDoctrine()->getManager();
        $roles = $this->get('security.authorization_checker');
        $key_post = md5('post' . $id);
        $cache = $this->get('dmb_blog.checkcache');


        $post = $cache->checkIfPostStoredInCache($key_post, $em->getRepository('DMBBlogBundle:Post')->find($id), $this->get('security.authorization_checker'));

        $chapterNumber = $post->getChapterNumber();
        $chapterNavigation = $this->get('dmb_blog.getchapternavigation');
        $chapterNavigationArray = $chapterNavigation->getChapter($roles, $chapterNumber, $post, $id, $em, $cache);

        $previousChapter = $chapterNavigationArray["previousChapter"];
        $nextChapter = $chapterNavigationArray["nextChapter"];

        //comments part
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $addComment = $this->get('dmb_blog.commentsmanagement');
        $addComment->addComment($request, $id, $em, $post, $comment);

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
        $key = md5('menu_pages');
        $doctrine = $em->getRepository('DMBBlogBundle:Page')->findTwoPages();
        $roles  = $this->get('security.authorization_checker');
        $cache = $this->get('dmb_blog.checkcache');
        $pages = $cache->checkIfStoredInCacheByRoles($key, $doctrine, $roles);

        return $this->render('DMBBlogBundle:Default:menu.html.twig', compact('pages'));
    }

    public function pageAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $key = md5('page' . $id);
        $doctrine = $em->getRepository('DMBBlogBundle:Page')->find($id);
        $roles = $this->get('security.authorization_checker');
        $cache = $this->get('dmb_blog.checkcache');
        $page = $cache->checkIfStoredInCacheByRoles($key, $doctrine, $roles);



        if ($page === null ) {
            throw new NotFoundHttpException("La page avec l'id " . $id . " n'existe pas.");
        }

        return $this->render('DMBBlogBundle:Default:page.html.twig', compact('page'));

    }
}
