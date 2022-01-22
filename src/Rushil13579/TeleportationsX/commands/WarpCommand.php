<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class WarpCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("warp");
        $this->setDescription(DataManager::getMessage("warp_description"));
        $this->setUsage(DataManager::getMessage("warp_usage"));
        $this->setPermission("teleportationsx.warp");
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
            $sender->sendMessage(DataManager::getMessage("warp_usage"));
            return;
        }

        $name = $args[0];

        $warpManager = TeleportationsX::getInstance()->getWarpManager();

        if(!$warpManager->warpExists($name)) {
            $sender->sendMessage(DataManager::getMessage("warp_doesnt_exist"));
            return;
        }

        if(!$sender->hasPermission("teleportationsx.warp.$name")) {
            $sender->sendMessage(DataManager::getMessage("no_perm_for_this_warp"));
            return;
        }

        $warp = $warpManager->getWarp($name);
        $sender->teleport($warp);
        $sender->sendMessage(DataManager::getMessage("teleported_to_warp_successfully", ["WARP" => $name]));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}