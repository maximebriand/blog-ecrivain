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
use DMB\BlogBundle\Entity\Comment;
use DMB\BlogBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class commentsManagement
{
    private $container;
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->container = new ContainerBuilder();
        $this->em = $em;
    }

    public function addComment(Request $request, $id,Post $post,Comment $comment)
    {
        // If it is a POST request we manage to add comment
        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            $comment //content is get from the form we add the date, the chapter (post) and the active user
            ->setMember($this->container->getUser())
                ->setDate(new \DateTime(('now')))
                ->setPost($post)
            ;


            if ($form->isValid()) {
                $this->em->persist($comment);
                $this->em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Votre commentaire a bien été enregistré.');
                return $this->container->redirectToRoute('dmb_blog_post', array('id' => $id));
            }

        }
    }
}
