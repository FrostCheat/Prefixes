<?php

namespace frostcheat\prefixes\command\subcommands;

use frostcheat\prefixes\libs\CortexPE\Commando\args\RawStringArgument;
use frostcheat\prefixes\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\prefixes\Prefixes;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class RemoveSubCommand extends BaseSubCommand
{
    public function __construct(Plugin $plugin)
    {
        parent::__construct($plugin, "remove", "Remove prefix to user");
        $this->setPermission("prefixes.command.remove");
        $this->setPermissionMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("no-permission-command-message"))));
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("playerName"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $player = Server::getInstance()->getPlayerExact($args["playerName"]);

        if ($player === null) {
            $player = Server::getInstance()->getOfflinePlayer($args["playerName"]);
        }

        if (!$player->hasPlayedBefore()) {
            $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-no-player"))));
            return;
        }

        $session = Prefixes::getInstance()->getSessionManager()->getSession((string)$player->getUniqueId());
        if ($session !== null) {
            if ($session->getPrefix() === null) {
                $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%player%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $player->getName()], Prefixes::getInstance()->getProvider()->getMessages()->get("player-remove-player-no-prefix"))));
                return;
            }
        }

        if ($player instanceof Player) {
            $session = Prefixes::getInstance()->getSessionManager()->getSession((string)$player->getUniqueId());
            if ($session !== null) {
                $prefix = $session->getPrefix();
                $session->setPrefix(null);
                $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%prefix-name%", "%player%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $prefix, $player->getName()], Prefixes::getInstance()->getProvider()->getMessages()->get("player-remove-succesfuly"))));
            }
        } else {
            foreach (Prefixes::getInstance()->getSessionManager()->getSessions() as $uuid => $data) {
                if (strtolower($player->getName()) === strtolower($data["name"])) {
                    $prefix = $data["prefix"];
                    $data["prefix"] = null;
                    $sender->sendMessage(TextFormat::colorize(str_replace(["%prefix-name%","%plugin-prefix%", "%player%"], [$prefix, Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $player->getName()], Prefixes::getInstance()->getProvider()->getMessages()->get("player-remove-succesfuly"))));
                    return;
                }
            }
        }
    }
}