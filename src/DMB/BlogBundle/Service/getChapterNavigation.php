<?php
/**
 * Created by PhpStorm.
 * User: maximebriand
 * Date: 26/05/2017
 * Time: 09:15
 */

namespace DMB\BlogBundle\Service;
/*
 *
 * Accessible via
 * $container->get('dmb_blog.getchapternavigation');
 *
*/


use DMB\BlogBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class getChapterNavigation
{

    private $em;
    private $cache;

    public function __construct(checkCache $cache, EntityManager $em)
    {
        $this->cache = $cache;
        $this->em = $em;
    }

    public function getChapter(AuthorizationCheckerInterface $roles, $chapterNumber,Post $post, $id)
    {

        if (
            ($roles->isGranted('ROLE_ADMIN') && $post !== null)
            || ($post !== null && $post->getPublishedDate() < (new \DateTime(('now'))))
        ) {
            //generate previous and next chapter
            //if it's an admin we display all chapter navigation button
            if ($roles->isGranted('ROLE_ADMIN')) {
                $previousChapter = $this->em->getRepository('DMBBlogBundle:Post')->findByIdChapterNumber($chapterNumber - 1);
                $nextChapter = $this->em->getRepository('DMBBlogBundle:Post')->findByIdChapterNumber($chapterNumber + 1);
            } elseif ($roles->isGranted('ROLE_USER')) {//if it's a registered user
                $key_post_previous_member = md5('posts_previous_anon' . $id);
                $key_post_next_member = md5('posts_next_anon' . $id);

                $previousChapter = $this->cache
                    ->checkIfStoredInCache($key_post_previous_member,
                        $this->em
                            ->getRepository('DMBBlogBundle:Post')
                            ->findByIdChapterNumberUser($chapterNumber - 1)
                    );
                $nextChapter = $this->cache
                    ->checkIfStoredInCache($key_post_next_member,
                        $this->em
                            ->getRepository('DMBBlogBundle:Post')
                            ->findByIdChapterNumberUser($chapterNumber + 1)
                    );
            } else //if it's anon user
            {
                $key_post_previous_anon = md5('posts_previous_anon' . $id);
                $key_post_next_anon = md5('posts_next_anon' . $id);

                $previousChapter = $this->cache
                    ->checkIfStoredInCache($key_post_previous_anon,
                        $this->em
                            ->getRepository('DMBBlogBundle:Post')
                            ->findByIdChapterNumberUser($chapterNumber - 1)
                    );
                $nextChapter = $this->cache
                    ->checkIfStoredInCache($key_post_next_anon,
                        $this->em
                            ->getRepository('DMBBlogBundle:Post')
                            ->findByIdChapterNumberUser($chapterNumber + 1)
                    );
            }
        }

        $chapterArray = array(
            "previousChapter" => $previousChapter,
            "nextChapter" => $nextChapter
        );

        return $chapterArray;
    }
}