<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Dto\ViewThreadDto;
use App\Entity\ContentBit;
use App\Entity\Thread;
use App\Entity\ThreadConversation;
use App\Repository\ContentBitRepository;
use App\Repository\ThreadConversationRepository;
use App\Repository\ThreadRepository;
use App\Transformer\CommentTransformer;
use App\Transformer\ThreadTransformer;
use Doctrine\Common\Collections\Collection;

class ThreadService
{
    public function __construct(
        private ThreadRepository             $threadRepository,
        private ThreadTransformer            $transformer,
        private CommentTransformer           $commentTransformer,
        private UploadPictureServiceImpl     $uploadPictureServiceImpl,
        private ContentBitRepository         $contentBitRepository,
        private ThreadConversationRepository $threadConversationRepository,
    )
    {
    }

    public function getAllThreads(string $username, int $page): array
    {
        $threads = $this->threadRepository->getAllThreadsWithPage($username, $page);
        if ($threads) {
            $threadDTOs = [];
            foreach ($threads as $thread) {
                $threadDTOs[] = $this->transformer->transformThreadIntoSearchDto($thread);
            }

            return $threadDTOs;
        }

        return [];
    }

    public function getChatThreads(string $username): array
    {
        $threads = $this->threadRepository->getUsersOpenThreads($username);
        if ($threads) {
            $threadDTOs = [];
            foreach ($threads as $thread) {
                $threadDTOs[] = $this->transformer->transformThreadIntoChatDto($thread);
            }

            return $threadDTOs;
        }

        return [];
    }

    public function createThread(Thread $thread): bool
    {
        $thread->setUploadDate(date_create());
        return $this->threadRepository->createThread($thread);
    }

    public function setThreadContent(Thread &$thread, array $text, array $images): void
    {
        $imageNumber = 0;
        for ($i = 0; $i < sizeof($text); $i++) {
            if ($text[$i] != null) {
                $contentBit = new ContentBit();
                $contentBit->setThread($thread);
                $contentBit->setType('text');
                $contentBit->setContent($text[$i]);
                $thread->addContent($contentBit);
                $this->contentBitRepository->save($contentBit, true);
            }
            if ($imageNumber < sizeof($images)) {
                $contentBit = new ContentBit();
                $contentBit->setThread($thread);
                $contentBit->setType('image');
                $contentBit->setContent($images[$imageNumber]);
                $thread->addContent($contentBit);
                $this->contentBitRepository->save($contentBit, true);
                $imageNumber++;
            }

        }
    }

    public function getSearchedThreads(string $username, string $searchQuery, array $words, int $page): array
    {
        $threads = $this->threadRepository->getSearchedThreadsWithPage($username, $searchQuery, $words, $page);

        if ($threads) {
            $threadDTOs = [];
            foreach ($threads as $thread) {
                $threadDTOs[] = $this->transformer->transformThreadIntoSearchDto($thread);
            }

            return $threadDTOs;
        }

        return [];
    }

    public function getThreadDtoById(string $threadId): ViewThreadDto
    {
        $thread = $this->threadRepository->getThreadById($threadId);
        $comments = $thread->getComments();
        $commentDTOs = [];
        foreach ($comments as $comment) {
            $commentDTOs[] = $this->commentTransformer->transformCommentIntoDto($comment);
        }

        return $this->transformer->transformThreadIntoViewDto($thread, $commentDTOs);
    }

    public function getThreadObjectById(string $threadId): Thread
    {
        return $this->threadRepository->getThreadById($threadId);
    }

    public function deleteThread(string $threadId): void
    {
        $thread = $this->threadRepository->getThreadById($threadId);
        foreach ($thread->getContent() as $contentBit) {
            if ($contentBit->getType() == 'image') {
                $this->uploadPictureServiceImpl->deletePicture($contentBit->getContent());
            }
        }

        $this->threadRepository->deleteThread($threadId);
    }

    public function editThreadContent(Thread &$thread, array $newContent, array $images): void
    {
        $originalContent = clone $thread->getContent();
        $this->removeContent($thread);
        $this->deleteOldImages($originalContent, $images);
        $textNumber = 0;
        $imageNumber = 0;
        for ($i = 0; $i < sizeof($originalContent); $i++) {
            if ($originalContent[$i]->getType() == 'text') {
                for ($j = $textNumber; $j < sizeof($newContent); $j++) {
                    if (!is_null($newContent[$j])) {
                        $contentBit = new ContentBit();
                        $contentBit->setThread($thread);
                        $contentBit->setType('text');
                        $contentBit->setContent($newContent[$j]);
                        $thread->addContent($contentBit);
                        $this->contentBitRepository->save($contentBit, true);
                        $textNumber = $j + 1;
                        break;
                    }
                }
            } elseif ($originalContent[$i]->getType() == 'image') {
                for ($j = $imageNumber; $j < sizeof($images); $j++) {
                    if (!is_null($images[$j])) {
                        $contentBit = new ContentBit();
                        $contentBit->setThread($thread);
                        $contentBit->setType('image');
                        $contentBit->setContent($images[$j]);
                        $thread->addContent($contentBit);
                        $this->contentBitRepository->save($contentBit, true);
                        $imageNumber = $j + 1;
                        break;
                    }
                }
            }
        }
        for ($i = $textNumber; $i < sizeof($newContent); $i++) {
            if (!is_null($newContent[$i])) {
                $contentBit = new ContentBit();
                $contentBit->setThread($thread);
                $contentBit->setType('text');
                $contentBit->setContent($newContent[$i]);
                $thread->addContent($contentBit);
                $this->contentBitRepository->save($contentBit, true);
            }
        }
        for ($i = $imageNumber; $i < sizeof($images); $i++) {
            if (!is_null($images[$i])) {
                $contentBit = new ContentBit();
                $contentBit->setThread($thread);
                $contentBit->setType('image');
                $contentBit->setContent($images[$i]);
                $thread->addContent($contentBit);
                $this->contentBitRepository->save($contentBit, true);
            }
        }
    }

    private function removeContent(Thread &$thread): void
    {
        $this->contentBitRepository->removeThreadTextAndImages($thread->getId());
        $thread->removeTextAndImages();
    }

    private function deleteOldImages(Collection $originalContent, array $newImages): void
    {
        foreach ($originalContent as $contentBit) {
            if ($contentBit->getType() == 'image') {
                if (!in_array($contentBit->getContent(), $newImages)) {
                    $this->uploadPictureServiceImpl->deletePicture($contentBit->getContent());
                }
            }
        }
    }

    public function addThreadConversation(Thread $thread, ThreadConversation $conversation, bool $anonymous): void
    {
        if ($anonymous) {
            $conversation->setHelper(null);
            $this->threadConversationRepository->save($conversation);
        }

        $contentBit = new ContentBit();
        $contentBit->setThread($thread);
        $contentBit->setType('conversation');
        $contentBit->setConversation($conversation);

        $this->contentBitRepository->save($contentBit, true);

        $conversation->setContentBit($contentBit);
        $thread->addContent($contentBit);
        $this->contentBitRepository->save($contentBit, true);

        $this->threadConversationRepository->save($conversation, true);
    }
}
