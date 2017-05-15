<?php

namespace DMB\BlogBundle\Service\NotificationEmail;

/*
 *
 * Accessible via
 * $container->get('dmb_blog.checknewchapter');
 *
*/

use Symfony\Component\Templating\EngineInterface;


class CheckNewChapter

{
    protected $mailer;

    protected $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;

        $this->templating = $templating;
    }

    public function sendMessage($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');

        $this->mailer->send($mail);

    }

}