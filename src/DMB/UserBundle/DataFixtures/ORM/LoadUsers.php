<?php

namespace DMBUserBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DMB\UserBundle\Entity\User;


class LoadUsers  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        // 'Jean Forteroche' is the admin user allowed to access the admin
        $user = new User();
        $user
            ->setUsername('Jean Forteroche')
            ->setEmail('jean.forteroche@maximebriand.fr')
            ->setRoles(array('ROLE_ADMIN'))
            ->setUpdatedAt(new \DateTime("now"))
            ->setEnabled(true)
            ->setPassword($encoder->encodePassword($user, '1234'))
            ->setImage("jean_forteroche.jpg")
        ;

        $manager->persist($user);

        foreach (range(0, 9) as $i)
        {
            $plainPassword = 'password'.$i;
            $encodedPassword = $encoder->encodePassword($user, $plainPassword);

            $user = new User();
            $user
                ->setUsername('user'.$i)
                ->setEmail('user'.$i.'@example.com')
                ->setRoles(array('ROLE_USER'))
                ->setEnabled(true)
                ->setUpdatedAt(new \DateTime("now"))
                ->setPassword($encodedPassword)
                ->setImage("avatar_" . ($i + 1) .".jpg")
            ;

            $this->addReference('user-'.$i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //User must be created first, then chapter and finally comments
        return 1;
    }
}