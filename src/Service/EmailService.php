<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailService

{

    Private $mailer;                //declaration de la variable mailer
    Private $adminEmail;
    Private $env;
    private $translator;
    private $DOMAIN_NAME;

    public function __construct($DOMAIN_NAME, MailerInterface $mailer, $adminEmail, $env, TranslatorInterface $translator)
    {
    //injection de service dans un service. nous utilisons la fonction __construct.
        
        $this->mailer = $mailer;   
        $this->DOMAIN_NAME = $DOMAIN_NAME; 
        $this->adminEmail = $adminEmail;
        $this->env = $env;
        $this->translator = $translator;
        
    }

    public function send(array $data): bool  {
    // Nous allons appeler l'emailservice pour qu'il envoi l'email: nous allons créer la function send

        if ($this->env === 'dev' || !isset($data['to'])) 
        {
            $to = $this->adminEmail;
        } 
        
        else 
        {
            $to = $data['to'];
        }

        $subject = '';

        if (isset($data['subject'])) 
        {
            $subject = $this->translator->trans($data['subject']);
        }

        $context = $data['context'] ?? [];
        $context ['DOMAIN_NAME'] = $this->DOMAIN_NAME;


        $email = (new TemplatedEmail())
        // Fonction d'envoi mail
            ->from($this->adminEmail)
            ->to($to)
            ->replyTo($data['replyTo'] ?? $this->adminEmail)
            ->subject($subject)
            ->htmlTemplate($data['template'])
            ->context($context);
            
        $this->mailer->send($email);
    
        return true;
    }
}