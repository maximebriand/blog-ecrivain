<?php
/**
 * Created by PhpStorm.
 * User: maximebriand
 * Date: 30/05/2017
 * Time: 10:14
 */

namespace DMB\BlogBundle\Service;
/*
 *
 * Accessible via
 * $container->get('dmb_blog.commentsmanagement');
 *
*/
use DMB\BlogBundle\Form\CommentType;
use DMB\BlogBundle\Entity\Comment;

class commentsManagement
{
    public function addComment($request, $id, $em, $container, $post,Comment $comment)
    {
        // If it is a POST request we manage to add comment
        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            $comment //content is get from the form we add the date, the chapter (post) and the active user
            ->setMember($container->getUser())
                ->setDate(new \DateTime(('now')))
                ->setPost($post)
            ;


            if ($form->isValid()) {
                $em->persist($comment);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Votre commentaire a bien été enregistré.');
                return $container->redirectToRoute('dmb_blog_post', array('id' => $id));
            }

        }
    }
}