<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Dto\ThreadMessageDto;
use App\Entity\ThreadMessage;

class ThreadMessageTransformer
{
    public function transformThreadMessageIntoDto(ThreadMessage $message): ThreadMessageDto
    {
        return (new ThreadMessageDto())
            ->setContent($message->getContent())
            ->setMine($message->isAuthorMe());
    }
}
