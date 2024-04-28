<?php

namespace frostcheat\prefixes\command\subcommands;

use frostcheat\prefixes\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\prefixes\Prefixes;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class HelpSubCommand extends BaseSubCommand
{
    public function __construct(Plugin $plugin)
    {
        parent::__construct($plugin, "help", "List commands");
        $this->setPermission("prefixes.command.help");
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
        $messages = [
            "&bPrefixes List Commands",
            "&7/prefix help - &fShow this list of commands",
            "&7/prefix create [string: prefixName] [string: format] [string: permission] - &fCreate a prefix",
            "&7/prefix reload - &fReload all plugin",
            "&7/prefix set [string: playerName] [string: prefixName] - &fSet prefix a player",
            "&7/prefix remove [string: playerName] - &fRemove the prefix from a player",
            "&7/prefix delete [string: prefixName] - &fDelete a prefix",
            ];

        foreach ($messages as $message) {
            $sender->sendMessage(TextFormat::colorize($message));
        }
    }
}