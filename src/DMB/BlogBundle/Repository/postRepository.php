<?php

namespace DMB\BlogBundle\Repository;
use Doctrine\Common\Collections;

/**
 * postRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class postRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllActivePosts()
    {
        return $this->createQueryBuilder('post')
            ->where("post.isActivated = true")
            ->andWhere("post.publishedDate < CURRENT_TIMESTAMP()")
            ->getQuery()
            ->getResult();
    }

    //find next and previous for Admin
    public function findByIdChapterNumber($chapterNumber)
    {
        return $this->createQueryBuilder('post')
            ->where("post.chapterNumber = $chapterNumber")
            ->getQuery()
            ->getOneOrNullResult(); //use to fetch the object
    }

    //find next and previous for user
    public function findByIdChapterNumberUser($chapterNumber)
    {
        return $this->createQueryBuilder('post')
            ->where("post.chapterNumber = $chapterNumber")
            ->andWhere("post.isActivated = true")
            ->getQuery()
            ->getOneOrNullResult(); //use to fetch the object
    }

    //find next and previous for anon
    public function findByIdChapterNumberAnon($chapterNumber)
    {
        return $this->createQueryBuilder('post')
            ->where("post.chapterNumber = $chapterNumber")
            ->andWhere("post.isActivated = true")
            ->andWhere("post.isPremium = false")
            ->getQuery()
            ->getOneOrNullResult(); //use to fetch the object
    }

    public function findChapterToNotify()
    {
        return $this->createQueryBuilder('post')
            ->where("post.isActivated = true")
            ->andWhere("post.isNotified = false")
            ->andWhere("post.publishedDate < CURRENT_TIMESTAMP()")
            ->getQuery()
            ->getResult();
    }


}
