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
        $this->getContainer()->get('dmb_blog.checknewchapter')
            ->sendMessage(
                'danymaxbrice@gmail.com',
                'danymaxbrice@gmail.com',
                'test',
                'coucou'
            )
        ;
    }
}

