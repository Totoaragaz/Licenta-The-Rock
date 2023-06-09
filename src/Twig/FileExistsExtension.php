<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileExistsExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('file_exists', [$this, 'checkIfFileExists']),
        ];
    }

    public function checkIfFileExists(string $file): bool
    {
        return file_exists($file);
    }

}