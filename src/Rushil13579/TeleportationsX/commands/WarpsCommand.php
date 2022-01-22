<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class WarpsCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("warps");
        $this->setDescription(DataManager::getMessage("warps_description"));
        $this->setUsage(DataManager::getMessage("warps_usage"));
        $this->setAliases([
            "listwarps",
            "warplist"]);
        $this->setPermission("teleportationsx.warps");
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

        $warps = TeleportationsX::getInstance()->getWarpManager()->getAllWarps();

        $warpList = C::GREEN . "Warps: ";
        foreach ($warps as $warp) {
            $label = $warp["label"];
            if($sender->hasPermission("teleportationsx.warp.$label")) {
                $warpList .= C::DARK_AQUA . $label . C::WHITE . ", ";
            }
        }

        $sender->sendMessage(DataManager::getMessage("warp_list", ["WARPLIST" => $warpList]));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}