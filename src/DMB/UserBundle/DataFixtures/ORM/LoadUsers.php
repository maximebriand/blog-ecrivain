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
        $user->setUsername('Jean Forteroche');
        $user->setEmail('jean.forteroche@maximebriand.fr');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setImage("jean_forteroche.jpg");
        $user->setUpdatedAt(new \DateTime("now"));
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword($user, '1234'));
        $manager->persist($user);
        foreach (range(0, 9) as $i)
        {

            $user = new User();
            $user->setUsername('user'.$i);
            $user->setEmail('user'.$i.'@example.com');
            $user->setRoles(array('ROLE_USER'));
            $user->setEnabled(true);
            $user->setImage("avatar" . $i .".jpg");
            $user->setUpdatedAt(new \DateTime("now"));

            $plainPassword = 'password'.$i;
            $encodedPassword = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);

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