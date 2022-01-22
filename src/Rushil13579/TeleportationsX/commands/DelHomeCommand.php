<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class DelHomeCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("delhome");
        $this->setDescription(DataManager::getMessage("removehome_description"));
        $this->setUsage(DataManager::getMessage("removehome_usage"));
        $this->setAliases(["unsethome"]);
        $this->setPermission("teleportationsx.delhome");
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

        if(count($args) < 1) {
            $sender->sendMessage(DataManager::getMessage("removehome_usage"));
            return;
        }

        $name = $args[0];

        $homeManager = TeleportationsX::getInstance()->getHomeManager();

        if(!$homeManager->homeExists($sender->getName(), $name)) {
            $sender->sendMessage(DataManager::getMessage("home_doesnt_exist"));
            return;
        }

        $homeManager->removeHome($sender->getName(), $name);
        $sender->sendMessage(DataManager::getMessage("home_removed_succesfully"));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}