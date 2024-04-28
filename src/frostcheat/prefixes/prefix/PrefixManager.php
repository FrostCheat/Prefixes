<?php

namespace frostcheat\prefixes\prefix;

use frostcheat\prefixes\Prefixes;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

class PrefixManager
{
    private array $prefixes = [];

    public function __construct()
    {
        # Register prefixes
        foreach (Prefixes::getInstance()->getProvider()->getPrefixes() as $name => $data) {
            $this->createPrefix((string) $name, $data);
            if ($data['permission'] !== null) {
                $this->registerPermission($data['permission']);
            }
        }
    }

    public function reload(): void
    {
        foreach ($this->prefixes as $name => $data) {
            unset($this->prefixes[$name]);
        }

        foreach (Prefixes::getInstance()->getProvider()->getPrefixes() as $name => $data) {
            $this->createPrefix((string) $name, $data);
            if ($data['permission'] !== null) {
                $this->registerPermission($data['permission']);
            }
        }
    }

    public function getPrefixes(): array
    {
        return $this->prefixes;
    }

    public function getPrefix(string $name): ?Prefix
    {
        return $this->prefixes[$name] ?? null;
    }

    public function createPrefix(string $name, array $data): void
    {
        $this->prefixes[$name] = new Prefix($name, $data);
    }

    public function registerPermission(string $permission): void {
        $manager = PermissionManager::getInstance();
        if ($manager->getPermission($permission) === null) {
            $manager->addPermission(new Permission($permission));
            $manager->getPermission(DefaultPermissions::ROOT_OPERATOR)->addChild($permission, true);
        }
    }

    public function removePrefix(string $name): void
    {
        unset($this->prefixes[$name]);
        foreach (Prefixes::getInstance()->getSessionManager()->getSessions() as $uuid => $data) {
            if ($data->getPrefix() === $name) {
                $data->setPrefix(null);
            }
        }

        if (file_exists(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes' . DIRECTORY_SEPARATOR . $name . '.yml')) {
            $result = unlink(Prefixes::getInstance()->getDataFolder() . DIRECTORY_SEPARATOR . 'prefixes' . DIRECTORY_SEPARATOR . $name . '.yml');

            if ($result) {
                Prefixes::getInstance()->getLogger()->debug('Prefix ' . $name . ' file deleted successfully');
            } else {
                Prefixes::getInstance()->getLogger()->debug('Error for deleted Prefix ' . $name . ' file');
            }
        }
    }
}