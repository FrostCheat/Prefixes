<?php

namespace frostcheat\prefixes;

use frostcheat\prefixes\command\PrefixCommand;
use frostcheat\prefixes\command\PrefixesCommand;
use frostcheat\prefixes\language\LanguageManager;
use frostcheat\prefixes\libs\CortexPE\Commando\PacketHooker;
use frostcheat\prefixes\libs\JackMD\ConfigUpdater\ConfigUpdater;
use frostcheat\prefixes\libs\JackMD\UpdateNotifier\UpdateNotifier;
use frostcheat\prefixes\libs\muqsit\invmenu\InvMenuHandler;
use frostcheat\prefixes\prefix\PrefixManager;
use frostcheat\prefixes\provider\Provider;
use frostcheat\prefixes\session\SessionManager;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\SingletonTrait;

class Prefixes extends PluginBase
{
    use SingletonTrait;
    public const CONFIG_VERSION = 3;

    private bool $charge = false;

    protected function onLoad(): void
    {
        self::setInstance($this);
        Provider::getInstance()->load();
        SessionManager::getInstance()->load();
        LanguageManager::getInstance()->load();
        PrefixManager::getInstance()->load();
    }

    public function onEnable(): void
    {
        UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
        if (ConfigUpdater::checkUpdate($this, $this->getConfig(), "config-version", self::CONFIG_VERSION)) {
            $this->reloadConfig();
        }

        if (!PacketHooker::isRegistered())
            PacketHooker::register($this);

        if (!InvMenuHandler::isRegistered())
            InvMenuHandler::register($this);

        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (): void {
            $this->getProvider()->save();
        }), 300 * 20);
        
        $this->getSessionManager()->checkRankSystem();

        $this->unregisterCommands(["prefix", "prefixes"]);
        $this->registerListeners([new EventListener()]);
        $this->getServer()->getCommandMap()->register("Prefixes", new PrefixCommand($this));
        $this->getServer()->getCommandMap()->register("Prefixes", new PrefixesCommand($this));
    }

    public function onDisable(): void
    {
        $this->getProvider()->save();
    }

    public function registerListeners(array $listener): void
    {
        foreach ($listener as $item) {
            if ($item instanceof Listener) {
                $this->getServer()->getPluginManager()->registerEvents($item, $this);
            }
        }
    }

    public function unregisterCommands(array $commands)
    {
        foreach ($commands as $command) {
            if ($this->getServer()->getCommandMap()->getCommand($command) !== null) {
                $this->getServer()->getCommandMap()->unregister($command);
            }
        }

    }

    /**
     * @return Provider
     */
    public function getProvider(): Provider
    {
        return Provider::getInstance();
    }

    /**
     * @return PrefixManager
     */
    public function getPrefixManager(): PrefixManager
    {
        return PrefixManager::getInstance();
    }

    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager
    {
        return SessionManager::getInstance();
    }

    /**
     * @return LanguageManager
     */
    public function getLanguageManager(): LanguageManager
    {
        return LanguageManager::getInstance();
    }

    public function isCharge(): bool
    {
        return $this->charge;
    }

    public function setCharge(bool $charge): void
    {
        if ($charge) {
            Prefixes::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (): void {
                Prefixes::getInstance()->setCharge(false);
            }), 200);
        }
        $this->charge = $charge;
    }
}