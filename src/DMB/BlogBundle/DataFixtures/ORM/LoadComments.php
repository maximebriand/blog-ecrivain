<?php

namespace DMBUserBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DMB\BlogBundle\Entity\Comment;


class LoadComments  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $em = $this->container->get('doctrine')->getEntityManager('default');
        $user_list = $em->getRepository('DMBUserBundle:User')->findAll();
        $post_list = $em->getRepository('DMBBlogBundle:Post')->findAll();

        $comment = new Comment();
        $comment
            ->setContent('Jean Forteroche')
            ->setMember($user_list[1])
            ->setDate(new \DateTime('now'))
            ->setPost($post_list[1])
        ;

        $manager->persist($comment);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //User must be created first, then chapter and finally comments
        return 3;
    }
}