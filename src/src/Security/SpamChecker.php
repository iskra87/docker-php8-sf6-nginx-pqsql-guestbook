<?php

namespace App\Security;

use App\Entity\Comment;

class SpamChecker
{
    public function getSpamScore(Comment $comment, array $getContext): int
    {
        return 0;//todo
    }
}