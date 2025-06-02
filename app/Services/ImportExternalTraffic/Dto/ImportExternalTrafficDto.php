<?php

namespace App\Services\ImportExternalTraffic\Dto;

class ImportExternalTrafficDto
{
    private string $platform;
    private int $sellerId;
    private string $filePath;

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function setPlatform(string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function setSellerId(int $sellerId): self
    {
        $this->sellerId = $sellerId;

        return $this;
    }

    public function setFilePath(string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }
}
