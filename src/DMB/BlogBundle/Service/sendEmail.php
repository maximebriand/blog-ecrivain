<?php

namespace DMB\BlogBundle\Service;

/*
 *
 * Accessible via
 * $container->get('dmb_blog.sendemail');
 *
*/

use Symfony\Component\Templating\EngineInterface;


class sendEmail

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
