<?php
/**
 * Created by PhpStorm.
 * User: maximebriand
 * Date: 26/05/2017
 * Time: 15:55
 */

namespace DMB\BlogBundle\Service;
/*
 *
 * Accessible via
 * $container->get('dmb_blog.checkpath');
 *
*/

use DMB\BlogBundle\Entity\Post;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class checkPath
{

    public function isProtected($path, AuthorizationCheckerInterface $role, Post $post)
    {

        if (preg_match("#draft#", $path))
        {
            if($role->isGranted('ROLE_ADMIN'))
            {
                $isGranted = $post;
            }else
            {
                $isGranted = null;
            }
        }
        elseif (preg_match("#premium#", $path))
        {
            if($role->isGranted('ROLE_USER'))
            {
                $isGranted = $post;
            }else
            {
                $isGranted = null;
            }
        }
        elseif (preg_match("/\bpost\b/i", $path))
        {
            $isGranted = $post;
        }
        else
        {
            $isGranted = null;
        }

        return $isGranted;
    }
}