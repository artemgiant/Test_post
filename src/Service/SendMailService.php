<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Swift_Message;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;


class SendMailService
{
    private $entityManager;

    /**
     * @var Swift_Mailer $mailer
     */
    private $mailer;

        /**
     * @var EngineInterface $templateEngine
     */
    private $templateEngine;
    
    /**
     * @param Swift_Mailer $mailer
     * @return $this
     */
    public function setMailer(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        return $this;
    }
    
    /**
     * @param EngineInterface $templateEngine
     * @return $this
     */
    public function setTemplateEngine(EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
        return $this;
    }
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

/**
     * @param User $user
     * @param $password
     */
    public function sendRecoverPassword($user, $password)
    {

        $template = $this->templateEngine->render('Email/password.html.twig');
        $template = str_replace("{{name}}", $user->getFullname(), $template);
        $template = str_replace("{{password}}", $password, $template);

        $message = Swift_Message::newInstance()
            ->setSubject('Password recovery in  site.')
            ->setFrom($this->senderEmail, $this->senderName)
            ->setTo($user->getEmail())
            ->setBody(html_entity_decode($template), 'text/html');

        $this->send($message);
    }
    
     /**
     * @param Swift_Message $message
     *
     * @return int
     */
    private function send(Swift_Message $message)
    {
        return $this->mailer->send($message);
    }

}
