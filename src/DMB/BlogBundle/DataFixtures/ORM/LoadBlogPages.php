<?php

namespace DMBBlogBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DMB\BlogBundle\Entity\Page;


class LoadBlogPages  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        $page = new Page();
        $page
            ->setTitle('A propos de ce livre')
            ->setContent('<p>Minions ipsum sit amet poulet tikka masala belloo! Aaaaaah. Bappleees gelatooo jeje exercitation. Ad daa butt magna sed tatata bala tu elit bappleees potatoooo. Quis me want bananaaa! Aliqua sit amet consectetur bananaaaa commodo daa. Veniam et butt aaaaaah eiusmod. Qui magna officia voluptate hahaha. Gelatooo jiji aliquip consectetur.</p> <p>Labore commodo eiusmod sit amet officia uuuhhh. Cillum wiiiii underweaaar poulet tikka masala tatata bala tu exercitation enim jiji qui jeje veniam. Potatoooo veniam et belloo! Bee do bee do bee do quis eiusmod chasy consectetur. Occaecat ex potatoooo nisi pepete labore laboris potatoooo minim nisi. Potatoooo po kass bee do bee do bee do bee do bee do bee do labore adipisicing aute cillum baboiii jeje elit. Tatata bala tu enim wiiiii officia belloo! Elit veniam minim cillum.</p><p>Adipisicing officia qui incididunt la bodaaa uuuhhh jiji dolore. Adipisicing chasy quis aute adipisicing labore jeje. Laboris butt pepete butt aliquip uuuhhh irure esse. Et belloo! Hahaha bananaaaa daa veniam. Tatata bala tu incididunt quis irure tulaliloo. Jiji butt butt eiusmod jeje. Esse cillum tatata bala tu quis consectetur. Wiiiii bee do bee do bee do jiji ti aamoo! Tatata bala tu hana dul sae occaecat consequat aliqua. Gelatooo officia tatata bala tu incididunt aaaaaah hahaha occaecat chasy ullamco.</p>')
            ->setPosition(1)
            ->setEnabled(true)
        ;

        $manager->persist($page);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //User must be created first, then chapter and finally comments
        return 4;
    }
}