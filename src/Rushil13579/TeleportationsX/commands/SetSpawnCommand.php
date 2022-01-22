<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class SetSpawnCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("setspawn");
        $this->setDescription(DataManager::getMessage("setspawn_description"));
        $this->setUsage(DataManager::getMessage("setspawn_usage"));
        $this->setPermission("teleportationsx.setspawn");
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

        TeleportationsX::getInstance()->getSpawnManager()->setSpawn($sender->getPosition());
        $sender->sendMessage(DataManager::getMessage("spawn_set_successfully"));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}