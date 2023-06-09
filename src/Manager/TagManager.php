<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Thread;
use App\Service\Implementations\TagService;
use Doctrine\Common\Collections\Collection;

class TagManager
{
    public function __construct(
        private TagService $tagService,
    )
    {
    }

    public function createTags(string $tagText): Collection
    {
        $tags = explode(',', str_replace(' ', '', strtolower($tagText)));
        return $this->tagService->createTags($tags);
    }

    public function saveTags(Thread $thread): void
    {
        $this->tagService->saveTags($thread);
    }
}
