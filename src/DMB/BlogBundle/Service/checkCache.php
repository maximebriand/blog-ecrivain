<?php
/**
 * Created by PhpStorm.
 * User: maximebriand
 * Date: 22/05/2017
 * Time: 16:59
 */

namespace DMB\BlogBundle\Service;
/*
 *
 * Accessible via
 * $container->get('dmb_blog.checkcache');
 *
*/



use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class checkCache
{
    private $cache;

    public function __construct($cache) {
        $this->cache = $cache;
    }

    public function checkIfStoredInCache($key, $doctrine) {
        if($this->cache->contains($key))
        {
            $value = $this->cache->fetch($key);
        } else
        {
            $value = $doctrine;
            $this->cache->save($key, $value);
        }
        return $value;
    }

    public function checkIfStoredInCacheByRoles ($key, $doctrine, AuthorizationCheckerInterface $roles)
    {
        if ($roles->isGranted('ROLE_ADMIN'))
        {
            $value = $doctrine;
        }
        else
        {
            $value = $this->checkIfStoredInCache($key, $doctrine);
        }

        return $value;
    }
}