<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use Rushil13579\TeleportationsX\TeleportationsX;
use pocketmine\utils\TextFormat as C;

class HomesCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("homes");
        $this->setDescription("Get a list of all available homes");
        $this->setUsage("/homes");
        $this->setAliases(["listhomes", "homelist"]);
        $this->setPermission("teleportationsx.homes");
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

        $homes = TeleportationsX::getInstance()->getHomeManager()->getAllHomes($sender->getName());

        $homeList = C::GREEN . "Homes: ";
        foreach($homes as $home){
            $homeList .= C::DARK_AQUA . $home["label"] . C::WHITE . ", ";
        }

        $sender->sendMessage($homeList);
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}