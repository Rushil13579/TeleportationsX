<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use Rushil13579\TeleportationsX\TeleportationsX;
use pocketmine\utils\TextFormat as C;

class WarpsCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("warps");
        $this->setDescription("Get a list of all available warps");
        $this->setUsage("/warps");
        $this->setAliases(["listwarps", "warplist"]);
        $this->setPermission("teleportationsx.warps");
        $this->setPermissionMessage(C::RED . "You don't have permission to use this command");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof Player){
            $sender->sendMessage(C::RED . "Please use this command in-game");
            return;
        }

        if(!$this->testPermission($sender))
            return;

        $warps = TeleportationsX::getInstance()->getWarpManager()->getAllWarps();

        $warpList = C::GREEN . "Warps: ";
        foreach($warps as $warp){
            $warpList .= C::DARK_AQUA . $warp["label"] . C::WHITE . ", ";
        }

        $sender->sendMessage($warpList);
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}