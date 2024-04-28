<?php

namespace frostcheat\prefixes\session;

class Session
{
    private string $uuid;
    private string $name;
    private ?string $prefix;

    public function __construct(string $uuid, array $data)
    {
        $this->uuid = $uuid;
        $this->name = $data["name"];
        $this->prefix = $data["prefix"];
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getData(): array
    {
        $data = [
            'name' => $this->getName(),
            'prefix' => $this->getPrefix(),
        ];
        return $data;
    }
}