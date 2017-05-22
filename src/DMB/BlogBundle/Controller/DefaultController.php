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

        $key_post = md5('post' . $id);

        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $post = $em->getRepository('DMBBlogBundle:Post')->find($id);
            if (null === $post) {
                throw new NotFoundHttpException("Le chapitre avec l'id " . $id . " n'a pas encore été rédigé.");
            }
        } else
        {
            //cache is used only for non admin user
            $cache = $this->get('dmb_blog.checkcache');
            $doctrine = $em->getRepository('DMBBlogBundle:Post')->find($id);
            $post = $cache->checkIfStoredInCache($key_post, $doctrine);
        }

        $chapterNumber = $post->getChapterNumber();

        if (
            ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') and $post !== null)
            or ($post !== null and $post->getPublishedDate() < (new \DateTime(('now'))))
        )
        {
            //generate previous and next chapter
            //if it's an admin we display all chapter navagation button
            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            {
                $previousChapter = $em->getRepository('DMBBlogBundle:Post')->findByIdChapterNumber($chapterNumber - 1);
                $nextChapter = $em->getRepository('DMBBlogBundle:Post')->findByIdChapterNumber($chapterNumber + 1);
            }
            elseif ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            {//if it's a registered user
                $key_post_member = md5('posts_member' . $id);
                $key_post_previous_member = md5('posts_previous_anon' . $id);
                $key_post_next_member = md5('posts_next_anon' . $id);

                //cache for post
                $cache = $this->get('dmb_blog.checkcache');


                $doctrine = $em->getRepository('DMBBlogBundle:Post')->find($id);
                $post = $cache->checkIfStoredInCache($key_post_member, $doctrine);

                $previousChapter = $cache
                    ->checkIfStoredInCache($key_post_previous_member,
                        $em
                            ->getRepository('DMBBlogBundle:Post')
                            ->findByIdChapterNumberUser($chapterNumber - 1)
                    )
                ;
                $nextChapter = $cache
                    ->checkIfStoredInCache($key_post_next_member,
                        $em
                            ->getRepository('DMBBlogBundle:Post')
                            ->findByIdChapterNumberUser($chapterNumber + 1)
                    )
                ;
            }
            else //if it's anon user
            {
                $key_post_anon = md5('posts_anon' . $id);
                $key_post_previous_anon = md5('posts_previous_anon' . $id);
                $key_post_next_anon = md5('posts_next_anon' . $id);

                //cache for post
                $cache = $this->get('dmb_blog.checkcache');


                $doctrine = $em->getRepository('DMBBlogBundle:Post')->find($id);
                $post = $cache->checkIfStoredInCache($key_post_anon, $doctrine);

                $previousChapter = $cache
                    ->checkIfStoredInCache($key_post_previous_anon,
                        $em
                            ->getRepository('DMBBlogBundle:Post')
                            ->findByIdChapterNumberUser($chapterNumber - 1)
                    )
                ;
                $nextChapter = $cache
                    ->checkIfStoredInCache($key_post_next_anon,
                        $em
                            ->getRepository('DMBBlogBundle:Post')
                            ->findByIdChapterNumberUser($chapterNumber + 1)
                    )
                ;
            }

            //comments part
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
        } else
        {
            throw new NotFoundHttpException("Le chapitre avec l'id " . $id . " n'a pas encore été rédigé.");
        }
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('DMBBlogBundle:Page')->findTwoPages();

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
