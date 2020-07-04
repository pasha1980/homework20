<?php


namespace App\Service;


interface ContentInterface
{
    public function getContent(): ?string;
    public function setContent(string $content): self;
}