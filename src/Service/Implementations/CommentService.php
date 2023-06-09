<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Entity\Comment;
use App\Repository\CommentRepository;

class CommentService
{
    public function __construct(
        protected CommentRepository $commentRepository
    )
    {
    }

    public function submitComment(Comment $comment): bool
    {
        $comment->setUploadDate(date_create());
        return $this->commentRepository->saveComment($comment);
    }

    public function deleteComment(string $commentId): void
    {
        $this->commentRepository->deleteComment($commentId);
    }
}
