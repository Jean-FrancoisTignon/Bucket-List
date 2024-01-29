<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;

class Sender
{
    public function __construct(protected MailerInterface $mailer)
    {
    }

    public function sendNewUserNotificationToAdmin(UserInterface $user) : void
    {
        $message = new Email();
        $message->from('accounts@bucket-list.com')
                ->to('admin@admin.fr')
                ->subject('nouveau compte crée sur Bucket-list.fr !')
                ->html('<h2>Nouveau compte !</h2>
                              <h3>email : ' . $user->getEmail() . '</h3>
                              <h3>pseudo : ' . $user->getPseudo() . '</h3>');
        $this->mailer->send($message);
    }
}