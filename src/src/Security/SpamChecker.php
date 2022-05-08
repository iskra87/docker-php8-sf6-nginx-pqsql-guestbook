<?php

namespace App\Security;

use App\Entity\Comment;

class SpamChecker
{
    public const MIGHT_BE_SPAM = 1;
    public const REJECT_SPAM = 2;
    public const NOT_SPAM = 0;

    public function getSpamScore(Comment $comment, array $getContext): int
    {
        return self::NOT_SPAM; //todo
    }
}