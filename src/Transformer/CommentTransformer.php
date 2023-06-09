<?php

namespace App\Transformer;

use App\Dto\CommentDto;
use App\Entity\Comment;

class CommentTransformer
{
    public function transformCommentIntoDto(Comment $comment): CommentDto
    {
        return (new CommentDto())
            ->setId($comment->getId())
            ->setAuthor($comment->getAuthor()->getUsername())
            ->setUploadDate($comment->getUploadDate())
            ->setContent($comment->getContent())
            ->setAuthorImage($comment->getAuthor()->getImage());
    }
}