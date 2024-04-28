<?php

namespace frostcheat\prefixes\prefix;

class Prefix
{
    private string $name;
    private ?string $format = null;
    private ?string $permission = null;

    public function __construct(string $name, array $data)
    {
        $this->name = $name;
        $this->format = $data['format'];
        $this->permission = $data['permission'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function getData(): array
    {
        $data = [
            'format' => $this->getFormat(),
            'permission' => $this->getPermission()
        ];
        return $data;
    }
}