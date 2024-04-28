<?php

namespace frostcheat\prefixes\command;

use frostcheat\prefixes\libs\CortexPE\Commando\BaseCommand;
use frostcheat\prefixes\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;
use frostcheat\prefixes\libs\muqsit\invmenu\InvMenu;
use frostcheat\prefixes\libs\muqsit\invmenu\transaction\InvMenuTransaction;
use frostcheat\prefixes\libs\muqsit\invmenu\transaction\InvMenuTransactionResult;
use frostcheat\prefixes\libs\muqsit\invmenu\type\InvMenuTypeIds;
use frostcheat\prefixes\Prefixes;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class PrefixesCommand extends BaseCommand
{
    public function __construct(protected Plugin $plugin)
    {
        parent::__construct($this->plugin, "prefixes", "Prefixes list");
        $this->setPermission("prefixes.command");
        $this->setPermissionMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("no-permission-command-message"))));
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $i = 0;
        foreach (Prefixes::getInstance()->getPrefixManager()->getPrefixes() as $prefix => $data) {
            if ($i < 54) {
                $menu->getInventory()->setItem($i, VanillaBlocks::BEACON()->asItem()->setCustomName(TextFormat::colorize($prefix))->setLore([
                    TextFormat::colorize("&8Prefix"),
                    " ",
                    TextFormat::colorize("&7Format: ".$data->getFormat(),)
                ]));
                $i++;
            }
        }
        $menu->setListener(function (InvMenuTransaction $transaction): InvMenuTransactionResult {
            $player = $transaction->getPlayer();
            $item = $transaction->getItemClicked();
            $prefix = Prefixes::getInstance()->getPrefixManager()->getPrefix($item->getCustomName());
            $session = Prefixes::getInstance()->getSessionManager()->getSession((string)$player->getUniqueId());
            if ($prefix !== null) {
                if ($player->hasPermission($prefix->getPermission())) {
                    if ($session !== null) {
                        $session->setPrefix($prefix->getName());
                        $player->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%prefix%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $prefix->getFormat()], Prefixes::getInstance()->getProvider()->getMessages()->get("player-sets-prefix-succesfuly"))));
                        return $transaction->discard();
                    }
                } else {
                    $player->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("player-sets-prefix-no-permission"))));
                    return $transaction->discard();
                }
            }
            return $transaction->discard();
        });
        if ($sender instanceof Player) {
            $menu->send($sender, TextFormat::colorize("&aPrefixes"));
        }
    }
}