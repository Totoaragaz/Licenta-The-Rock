<?php

namespace App\Transformer;

use App\Dto\ThreadDto;
use App\Entity\Thread;

class ThreadTransformer
{
    public function transformThreadIntoDto(Thread $thread): ThreadDto
    {
        return (new ThreadDto())
            ->setTitle($thread->getTitle())
            ->setTags($thread->getTagNames())
            ->setUploadDate($thread->getUploadDate())
            ->setAuthor($thread->getAuthor()->getUsername());
    }
}