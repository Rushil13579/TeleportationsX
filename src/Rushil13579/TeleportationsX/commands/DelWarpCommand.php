<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class DelWarpCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("delwarp");
        $this->setDescription(DataManager::getMessage("removewarp_usage"));
        $this->setUsage(DataManager::getMessage("removewarp_description"));
        $this->setAliases([
            "unsetwarp",
            "removewarp"]);
        $this->setPermission("teleportationsx.delwarp");
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
            $sender->sendMessage(DataManager::getMessage("removewarp_usage"));
            return;
        }

        $name = $args[0];

        $warpManager = TeleportationsX::getInstance()->getWarpManager();

        if(!$warpManager->warpExists($name)) {
            $sender->sendMessage(DataManager::getMessage("warp_doesnt_exist"));
            return;
        }

        $warpManager->removeWarp($name);
        $sender->sendMessage(DataManager::getMessage("warp_removed_successfully"));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}