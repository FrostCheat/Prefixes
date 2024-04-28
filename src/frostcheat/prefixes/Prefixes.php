<?php

namespace frostcheat\prefixes;

use frostcheat\prefixes\command\PrefixCommand;
use frostcheat\prefixes\command\PrefixesCommand;
use frostcheat\prefixes\libs\CortexPE\Commando\PacketHooker;
use frostcheat\prefixes\libs\muqsit\invmenu\InvMenuHandler;
use frostcheat\prefixes\prefix\PrefixManager;
use frostcheat\prefixes\provider\Provider;
use frostcheat\prefixes\session\SessionManager;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class Prefixes extends PluginBase
{
    public static Prefixes $instance;
    private bool $charge = false;
    private Provider $provider;
    private PrefixManager $prefixManager;
    private SessionManager $sessionManager;

    protected function onLoad(): void
    {
        self::$instance = $this;
    }

    public function onEnable(): void
    {
        $this->provider = new Provider();
        $this->prefixManager = new PrefixManager();
        $this->sessionManager = new SessionManager();

        if (!PacketHooker::isRegistered())
            PacketHooker::register($this);

        if (!InvMenuHandler::isRegistered())
            InvMenuHandler::register($this);

        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (): void {
            $this->getProvider()->save();
        }), 300 * 20);

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

    public static function getInstance(): Prefixes
    {
        return self::$instance;
    }

    /**
     * @return Provider
     */
    public function getProvider(): Provider
    {
        return $this->provider;
    }

    /**
     * @return PrefixManager
     */
    public function getPrefixManager(): PrefixManager
    {
        return $this->prefixManager;
    }

    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
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