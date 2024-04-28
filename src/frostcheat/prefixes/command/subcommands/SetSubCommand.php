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

class SetSubCommand extends BaseSubCommand
{

    public function __construct(Plugin $plugin)
    {
        parent::__construct($plugin, "set", "Set prefix to user");
        $this->setPermission("prefixes.command.set");
        $this->setPermissionMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("no-permission-command-message"))));
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("playerName"));
        $this->registerArgument(1, new RawStringArgument("prefix"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $prefixName = $args["prefix"];

        $player = Server::getInstance()->getPlayerExact($args["playerName"]);

        if ($player === null) {
            $player = Server::getInstance()->getOfflinePlayer($args["playerName"]);
        }

        if (!$player->hasPlayedBefore()) {
            $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-no-player"))));
            return;
        }

        if (Prefixes::getInstance()->getPrefixManager()->getPrefix($prefixName) === null) {
            $sender->sendMessage(TextFormat::colorize(str_replace(["%prefix-name%", "%plugin-prefix%"], [$prefixName, Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix")], Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-no-prefix-exists"))));
            return;
        }


        if (Prefixes::getInstance()->getPrefixManager()->getPrefix($prefixName) !== null) {
            if ($player instanceof Player) {
                $session = Prefixes::getInstance()->getSessionManager()->getSession((string)$player->getUniqueId());
                if ($session !== null) {
                    $session->setPrefix($prefixName);
                }
            } else {
                foreach (Prefixes::getInstance()->getSessionManager()->getSessions() as $uuid => $data) {
                    if (strtolower($player->getName()) === strtolower($data["name"])) {
                        $data["prefix"] = $prefixName;
                    }
                }
            }
            $sender->sendMessage(TextFormat::colorize(str_replace(["%prefix-name%", "%player%", "%plugin-prefix%"], [$prefixName, $player->getName(), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix")], Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-succesfuly"))));
        }
    }
}