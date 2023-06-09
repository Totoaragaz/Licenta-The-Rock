<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Entity\Tag;
use App\Entity\Thread;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TagService
{
    public function __construct(
        private TagRepository $tagRepository,
    )
    {
    }

    public function createTags(array $stringTags): Collection
    {
        $tags = new ArrayCollection();
        foreach ($stringTags as $stringTag) {
            $tag = $this->tagRepository->findTag($stringTag);
            if ($tag == null) {
                $tag = new Tag();
                $tag->setName($stringTag);
                $this->tagRepository->createTag($tag);
            }

            $tags->add($tag);
        }
        return $tags;
    }

    public function saveTags(Thread $thread): void
    {
        foreach ($thread->getTags() as $tag) {
            $tag->addThread($thread);
            $this->tagRepository->updateTag($tag);
        }
    }
}
