<?php

namespace frostcheat\prefixes\command;

use frostcheat\prefixes\command\subcommands\CreateSubCommand;
use frostcheat\prefixes\command\subcommands\DeleteSubCommand;
use frostcheat\prefixes\command\subcommands\HelpSubCommand;
use frostcheat\prefixes\command\subcommands\ReloadSubCommand;
use frostcheat\prefixes\command\subcommands\RemoveSubCommand;
use frostcheat\prefixes\command\subcommands\SetSubCommand;
use frostcheat\prefixes\libs\CortexPE\Commando\BaseCommand;
use frostcheat\prefixes\Prefixes;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class PrefixCommand extends BaseCommand
{
    public function __construct(protected Plugin $plugin)
    {
        parent::__construct($this->plugin, "prefix", "Prefix commands");
        $this->setPermission("prefixes.command");
        $this->setPermissionMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("no-permission-command-message"))));
    }

    protected function prepare(): void
    {
        $this->registerSubCommand(new ReloadSubCommand($this->plugin));
        $this->registerSubCommand(new HelpSubCommand($this->plugin));
        $this->registerSubCommand(new CreateSubCommand($this->plugin));
        $this->registerSubCommand(new SetSubCommand($this->plugin));
        $this->registerSubCommand(new RemoveSubCommand($this->plugin));
        $this->registerSubCommand(new DeleteSubCommand($this->plugin));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("no-command-found"))));
    }
}