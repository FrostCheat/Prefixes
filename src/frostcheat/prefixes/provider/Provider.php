<?php

namespace frostcheat\prefixes\provider;

use frostcheat\prefixes\Prefixes;
use pocketmine\utils\Config;

class Provider
{
    private Config $messages;

    public function __construct()
    {
        if (!is_dir(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes'))
            @mkdir(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes');

        if (!is_dir(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'sessions'))
            @mkdir(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'sessions');

        Prefixes::getInstance()->saveDefaultConfig();
        Prefixes::getInstance()->saveResource("messages.yml");
    }

    public function save(): void
    {
        $this->saveSessions();
        $this->savePrefixes();
    }

    public function reload(): void
    {
        Prefixes::getInstance()->getConfig()->reload();
        $this->getMessages()->reload();
        Prefixes::getInstance()->setCharge(true);
    }

    public function getMessages(): Config
    {
        return new Config(Prefixes::getInstance()->getDataFolder() . "messages.yml", Config::YAML);
    }

    public function getSessions(): array
    {
        $sessions = [];

        foreach (glob(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'sessions' . DIRECTORY_SEPARATOR . '*.yml') as $file)
            $sessions[basename($file, '.yml')] = (new Config(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'sessions' . DIRECTORY_SEPARATOR . basename($file), Config::YAML))->getAll();
        return $sessions;
    }

    public function getPrefixes(): array
    {
        $prefixes = [];

        foreach (glob(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes' . DIRECTORY_SEPARATOR . '*.yml') as $file)
            $prefixes[basename($file, '.yml')] = (new Config(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes' . DIRECTORY_SEPARATOR . basename($file), Config::YAML))->getAll();
        return $prefixes;
    }

    public function savePrefixes(): void
    {
        foreach (Prefixes::getInstance()->getPrefixManager()->getPrefixes() as $name => $prefix) {
            $config = new Config(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes' . DIRECTORY_SEPARATOR . $name . '.yml', Config::YAML);
            $config->setAll($prefix->getData());
            $config->save();
        }
    }

    public function saveSessions(): void
    {
        foreach (Prefixes::getInstance()->getSessionManager()->getSessions() as $name => $sessions) {
            $config = new Config(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'sessions' . DIRECTORY_SEPARATOR . $name . '.yml', Config::YAML);
            $config->setAll($sessions->getData());
            $config->save();
        }
    }

}