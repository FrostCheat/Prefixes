<?php

namespace frostcheat\prefixes\provider;

use frostcheat\prefixes\language\LanguageManager;
use frostcheat\prefixes\Prefixes;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class Provider
{
    use SingletonTrait;

    private Config $messages;

    public function load(): void
    {
        if (!is_dir(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes'))
            @mkdir(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes');

        if (!is_dir(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'sessions'))
            @mkdir(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'sessions');

        $this->saveResources();
    }

    public function save(): void
    {
        $this->saveSessions();
        $this->savePrefixes();
    }

    public function reload(): void
    {
        $this->getMessages()->reload();
        Prefixes::getInstance()->getConfig()->reload();
        Prefixes::getInstance()->getPrefixManager()->reload();
        Prefixes::getInstance()->getSessionManager()->reload();
        Prefixes::getInstance()->getLanguageManager()->reload();
        Prefixes::getInstance()->setCharge(true);
    }

    public function saveResources(): void
    {
        Prefixes::getInstance()->saveDefaultConfig();
        Prefixes::getInstance()->saveResource("gui.yml");
        Prefixes::getInstance()->saveResource("languages/es_es.yml");
        Prefixes::getInstance()->saveResource("languages/en_us.yml");
        Prefixes::getInstance()->saveResource("languages/fr_fr.yml");
        Prefixes::getInstance()->saveResource("languages/gr_gr.yml");
        Prefixes::getInstance()->saveResource("languages/pr_br.yml");
        Prefixes::getInstance()->saveResource("languages/rs_rs.yml");
    }

    public function getMessages(): Config
    {
        return new Config(Prefixes::getInstance()->getDataFolder() . "languages" . DIRECTORY_SEPARATOR . LanguageManager::getInstance()->getDefaultLanguage() . ".yml", Config::YAML);
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

    public function getLanguages(): array
    {
        $languages = [];

        foreach (glob(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . '*.yml') as $file)
            $languages[basename($file, '.yml')] = (new Config(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . basename($file), Config::YAML))->getAll();
        return $languages;
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