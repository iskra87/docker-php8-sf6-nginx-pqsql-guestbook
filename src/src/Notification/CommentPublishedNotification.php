<?php

namespace App\Notification;

use App\Entity\Comment;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class CommentPublishedNotification extends Notification implements EmailNotificationInterface
{
    private Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        parent::__construct('Your comment published');
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        $this->importance(Notification::IMPORTANCE_LOW);
        return ['email'];
    }

    public function asEmailMessage(EmailRecipientInterface $recipient, string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient);
        $email = $message->getMessage();
        if ($email instanceof NotificationEmail) {
            $email->htmlTemplate('emails/comment_published.html.twig');
            $email->context(['comment' => $this->comment]);
        }
        return $message;
    }
}