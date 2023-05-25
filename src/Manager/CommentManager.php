<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Comment;
use App\Service\Implementations\CommentService;
use App\Service\Implementations\ThreadService;

class CommentManager
{
    public function __construct(
        protected CommentService $commentService,
        protected ThreadService $threadService,
    )
    {
    }

    public function submitComment(Comment $comment, string $threadId): bool
    {
        $comment->setThread($this->threadService->getThreadObjectById($threadId));
        return $this->commentService->submitComment($comment);
    }

    public function deleteComment(string $commentId): void
    {
        $this->commentService->deleteComment($commentId);
    }
}