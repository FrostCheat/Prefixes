<?php

namespace frostcheat\prefixes\session;

use frostcheat\prefixes\Prefixes;
use pocketmine\player\Player;

class Session
{
    private string $uuid;
    private string $name;
    private ?string $prefix;
    private SessionChatFormatter $chatFormatter;
    private Player $player;

    public function __construct(string $uuid, array $data)
    {
        $this->uuid = $uuid;
        $this->name = $data["name"];
        $this->prefix = $data["prefix"];

        $this->chatFormatter = new SessionChatFormatter($this);
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

    public function getChatFormatter(): SessionChatFormatter
    {
        return $this->chatFormatter;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    public function getChatFormat() : string {
        return Prefixes::getInstance()->getConfig()->getNested("chat-format", "%prefix%%name%%message%");
    }

    public function getData(): array
    {
        return [
            'name' => $this->getName(),
            'prefix' => $this->getPrefix(),
        ];
    }
}