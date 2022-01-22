<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class SpawnCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("spawn");
        $this->setDescription(DataManager::getMessage("spawn_description"));
        $this->setUsage(DataManager::getMessage("spawn_usage"));
        $this->setPermission("teleportationsx.spawn");
        $this->setPermissionMessage(DataManager::getMessage("no_perm"));
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof Player) {
            $sender->sendMessage(DataManager::getMessage("not_player"));
            return;
        }

        if(!$this->testPermission($sender))
            return;

        $spawnPos = TeleportationsX::getInstance()->getSpawnManager()->getSpawn();
        $sender->teleport($spawnPos);
        $sender->sendMessage(DataManager::getMessage("teleported_to_spawn_successfully"));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}