<?php

namespace frostcheat\prefixes\command\subcommands;

use frostcheat\prefixes\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\prefixes\Prefixes;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class ReloadSubCommand extends BaseSubCommand
{
    public function __construct(Plugin $plugin)
    {
        parent::__construct($plugin, "reload", "Reload all plugin");
        $this->setPermission("prefixes.command.reload");
        $this->setPermissionMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("no-permission-command-message"))));
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        // TODO: Implement prepare() method.
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        Prefixes::getInstance()->getProvider()->reload();
        $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-reload-message"))));
    }
}