<?php

namespace App\Tests\Transformer;

use App\Dto\SearchThreadDto;
use App\Entity\Thread;
use App\Entity\User;
use App\Transformer\ThreadTransformer;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ThreadTransformerTest extends TestCase
{
    public function testTransformThreadIntoSearchDto()
    {
        $author = new User();
        $author->setUsername('username');

        $thread = new Thread();
        $thread->setId(1);
        $thread->setTitle('title');
        $thread->setAuthor($author);
        $thread->setUploadDate(date_create('09/11/2001'));
        $thread->setTags(new ArrayCollection());

        $transformer = new ThreadTransformer();

        $generatedThreadDto = $transformer->transformThreadIntoSearchDto($thread);

        $threadDto = new SearchThreadDto();
        $threadDto->setId(1)
            ->setAuthor('username')
            ->setTitle('title')
            ->setTags([])
            ->setUploadDate(date_create('09/11/2001'));

        self::assertEquals($threadDto, $generatedThreadDto);
    }
}