<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

//declaration de la class
class EmailService

{
    //injection de service dans un service. nous utilisons la fonction __construct.
    //declaration de la variable mailer
    Private $mailer;
    Private $adminEmail;
    Private $env;
    private $translator;
    private $DOMAIN_NAME;

    public function __construct($DOMAIN_NAME, MailerInterface $mailer, $adminEmail, $env, TranslatorInterface $translator)
    {//est notre classe d'email service
        $this->mailer = $mailer;   
        $this->DOMAIN_NAME = $DOMAIN_NAME; 
        $this->adminEmail = $adminEmail;
        $this->env = $env;
        $this->translator = $translator;
        
    }
    //nous allons appeler l'emailservice pour qu'il envoi l'email: nous allons cree la function send
    public function send(array $data): bool  {

        if ($this->env === 'dev' || !isset($data['to'])) {
            $to = $this->adminEmail;
        } else {
            $to = $data['to'];
        }

        $subject = '';
        if (isset($data['subject'])) {
            $subject = $this->translator->trans($data['subject']);
        }

        $context = $data['context'] ?? [];
        $context ['DOMAIN_NAME'] = $this->DOMAIN_NAME;

        //dd('admin email : '.$this->adminEmail);
       // dd($_ENV['ADMIN_EMAIL']);to
        //dd($data);
        //function d'envoi mail
        $email = (new TemplatedEmail())
            ->from($this->adminEmail)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($data['replyTo'] ?? $this->adminEmail)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            
             // path of the Twig template to render
             ->htmlTemplate($data['template'])
            // pass variables (name => value) to the template
            ->context($context);
            
          
        $this->mailer->send($email);
        return true;
        }
    }

    //nous allons crée un dossier dans template appelé email pour le HtmlTemplate