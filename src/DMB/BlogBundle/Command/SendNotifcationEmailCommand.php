<?php
namespace DMB\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SendNotifcationEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('notification:check-new-chapter')

            // the short description shown while running "php bin/console list"
            ->setDescription('Check if there is a new chapter avaible.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command will send an email to all registered users when a new chapter is available.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $postToNotfied = $em->getRepository('DMBBlogBundle:Post')->findChapterToNotify();
        //$postToNotfied = $em->getRepository('DMBBlogBundle:Post')->findAll();
        $context = $this->getContainer()->get('router')->getContext();
        $baseUrl = $context->getBaseUrl();
        $emailContent;

        //if there is at least one new chapter
        if($postToNotfied)
        {
            $usersToNotified = $em->getRepository('DMBUserBundle:User')->findAll();
            $emailContent = null;

            if ($usersToNotified)
            {

                //for each post we create a li with data from the post
                foreach ($postToNotfied as $post)
                {
                    $emailContent .= "<li>Le chapitre numéro " . $post->getChapterNumber() . " a été mis en ligne: <a href='" . $baseUrl . $post->getUrl()  . "'>" . $post->getTitle() . "</a></li>";
                }

                foreach ( $usersToNotified as $user )
                {
                    $completeContent = "<p>Bonjour " . ucfirst($user->getUsername()) . " , un nouveau chapitre a été mis en ligne.</p><ul>" . $emailContent . "</ul>";
                    $userEmail = $user->getEmail();

                    $this->getContainer()->get('dmb_blog.checknewchapter')
                        ->sendMessage(
                            'danymaxbrice@gmail.com', //from
                            $userEmail, //to
                            'Un nouveau Chapitre a été mis en ligne !', //subject
                            $completeContent //body
                        )
                    ;
                }
            }
            foreach ($postToNotfied as $post)
            {
                $post->setIsNotified(true);
                $em->persist($post);
                $em->flush();
            }
        }
    }
}

