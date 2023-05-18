<?php

namespace App\Transformer;

use App\Dto\SearchThreadDto;
use App\Dto\ViewThreadDto;
use App\Entity\Thread;

class ThreadTransformer
{
    public function transformThreadIntoSearchDto(Thread $thread): SearchThreadDto
    {
        return (new SearchThreadDto())
            ->setId($thread->getId())
            ->setTitle($thread->getTitle())
            ->setTags($thread->getTagNames())
            ->setUploadDate($thread->getUploadDate())
            ->setAuthor($thread->getAuthor()->getUsername());
    }

    public function transformThreadIntoViewDto(Thread $thread): ViewThreadDto
    {
        return (new ViewThreadDto())
            ->setId($thread->getId())
            ->setTitle($thread->getTitle())
            ->setContent($thread->getContent())
            ->setTags($thread->getTagNames())
            ->setUploadDate($thread->getUploadDate())
            ->setAuthor($thread->getAuthor()->getUsername())
            ->setClosed($thread->isClosed());
    }
}