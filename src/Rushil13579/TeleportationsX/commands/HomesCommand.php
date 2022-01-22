<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class HomesCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("homes");
        $this->setDescription(DataManager::getMessage("homes_description"));
        $this->setUsage(DataManager::getMessage("homes_usage"));
        $this->setAliases([
            "listhomes",
            "homelist"]);
        $this->setPermission("teleportationsx.homes");
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

        $homes = TeleportationsX::getInstance()->getHomeManager()->getAllHomes($sender->getName());

        $homeList = "";
        foreach ($homes as $home) {
            $homeList .= $home["label"] . ", ";
        }

        $sender->sendMessage(DataManager::getMessage("home_list", ["{HOMELIST}" => $homeList]));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}