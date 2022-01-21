<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use Rushil13579\TeleportationsX\TeleportationsX;
use pocketmine\utils\TextFormat as C;

class AddWarpCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("addwarp");
        $this->setDescription("Add a new warp to the server");
        $this->setUsage("/addwarp [name]");
        $this->setAliases(["setwarp"]);
        $this->setPermission("teleportationsx.addwarp");
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

        if(count($args) < 1){
            $sender->sendMessage(C::RED . $this->getUsage());
            return;
        }

        $name = $args[0];

        $warpManager = TeleportationsX::getInstance()->getWarpManager();

        if($warpManager->warpExists($name)){
            $sender->sendMessage(C::RED . "This warp already exists. You must remove it first");
            return;
        }

        $warpManager->addWarp($name, $sender->getPosition());
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}