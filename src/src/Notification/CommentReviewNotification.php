<?php

namespace App\Notification;

use App\Entity\Comment;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\Button\InlineKeyboardButton;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\InlineKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class CommentReviewNotification extends Notification implements EmailNotificationInterface, ChatNotificationInterface
{
    private Comment $comment;
    private string $reviewUrl;
    private string $telegramChannel;

    public function __construct(Comment $comment, string $reviewUrl, string $telegramChannel)
    {
        $this->comment = $comment;
        $this->reviewUrl = $reviewUrl;
        $this->telegramChannel = $telegramChannel;

        parent::__construct('New comment posted');
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        if (preg_match('{\b(great|awesome)\b}i', $this->comment->getText())) {
            $this->importance(Notification::IMPORTANCE_HIGH);
            return ['email', 'chat/telegram'];
        }

        $this->importance(Notification::IMPORTANCE_LOW);

        return ['email'];
    }

    public function asEmailMessage(EmailRecipientInterface $recipient, string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient);
        $email = $message->getMessage();
        if ($email instanceof NotificationEmail) {
            $email->htmlTemplate('emails/comment_notification.html.twig');
            $email->context(['comment' => $this->comment]);
        }
        return $message;
    }

    public function asChatMessage(RecipientInterface $recipient, string $transport = null): ?ChatMessage
    {
        if ('telegram' !== $transport) {
            return null;
        }

        $message = ChatMessage::fromNotification($this);
        $message->options((new TelegramOptions())
//            ->replyMarkup((new InlineKeyboardMarkup())
//                ->inlineKeyboard([
//                    (new InlineKeyboardButton('accept'))->url($this->reviewUrl),
//                    (new InlineKeyboardButton('reject'))->url($this->reviewUrl . '?reject=1'),
//                ]))
            ->chatId($this->telegramChannel))
            ->subject(sprintf('%s (%s) says: %s', $this->comment->getAuthor(), $this->comment->getEmail(), $this->comment->getText())
            );

        return $message;
    }
}
