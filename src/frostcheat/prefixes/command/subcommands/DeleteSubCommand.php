<?php

namespace frostcheat\prefixes\command\subcommands;

use frostcheat\prefixes\libs\CortexPE\Commando\args\RawStringArgument;
use frostcheat\prefixes\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\prefixes\Prefixes;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class DeleteSubCommand extends BaseSubCommand
{
    public function __construct(Plugin $plugin)
    {
        parent::__construct($plugin, "delete", "Delete a prefix");
        $this->setPermission("prefixes.command.delete");
        $this->setPermissionMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("no-permission-command-message"))));
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("prefixName"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $prefixName = $args["prefixName"];

        if (Prefixes::getInstance()->getPrefixManager()->getPrefix($prefixName) === null) {
            $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%prefix-name%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $prefixName], Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-delete-no-exists"))));
            return;
        }

        Prefixes::getInstance()->getPrefixManager()->removePrefix($prefixName);
        $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%prefix-name%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $prefixName], Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-delete-succesfuly"))));
    }
}