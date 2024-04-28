<?php

namespace frostcheat\prefixes\session;

use frostcheat\prefixes\Prefixes;
use IvanCraft623\RankSystem\RankSystem;
use IvanCraft623\RankSystem\tag\Tag;
use IvanCraft623\RankSystem\session\Session as RankSession;
use pocketmine\utils\TextFormat;

class SessionManager
{
    /** @var Session[] */
    private array $sessions = [];

    /**
     * SessionManager construct.
     */
    public function __construct()
    {
        # Register players
        foreach (Prefixes::getInstance()->getProvider()->getSessions() as $uuid => $data)
            $this->addSession((string) $uuid, $data);

        if (Prefixes::getInstance()->getServer()->getPluginManager()->getPlugin("RankSystem") !== null) {
            RankSystem::getInstance()->getTagManager()->registerTag(new Tag("prefix", static function (RankSession $user): string {
                $session = Prefixes::getInstance()->getSessionManager()->getSession((string)$user->getPlayer()->getUniqueId());
                if ($session !== null) {
                    if ($session->getPrefix() !== null) {
                        if (Prefixes::getInstance()->getPrefixManager()->getPrefix($session->getPrefix()) !== null) {
                            $prefix = Prefixes::getInstance()->getPrefixManager()->getPrefix($session->getPrefix())->getFormat();
                        } else {
                            $prefix = "";
                        }
                    } else {
                        $prefix = "";
                    }
                } else {
                    $prefix = "";
                }
                return TextFormat::colorize($prefix);
            }));
        }
    }

    public function reload(): void
    {
        foreach ($this->sessions as $uuid => $data) {
            unset($this->sessions[$uuid]);
        }

        foreach (Prefixes::getInstance()->getProvider()->getSessions() as $uuid => $data) {
            $this->addSession((string) $uuid, $data);
        }
    }

    /**
     * @return array
     */
    public function getSessions(): array
    {
        return $this->sessions;
    }

    /**
     * @param string $uuid
     * @return Session|null
     */
    public function getSession(string $uuid): ?Session
    {
        return $this->sessions[$uuid] ?? null;
    }

    /**
     * @param string $uuid
     * @param array $data
     */
    public function addSession(string $uuid, array $data): void
    {
        $this->sessions[$uuid] = new Session($uuid, $data);
    }
}