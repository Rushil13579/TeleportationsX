<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;
use Rushil13579\TeleportationsX\TeleportationsX;

class SetSpawnCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("setspawn");
        $this->setDescription("Set the server spawn");
        $this->setUsage("/setspawn");
        $this->setPermission("teleportationsx.setspawn");
        $this->setPermissionMessage(C::RED . "You don't have permission to use this command");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof Player) {
            $sender->sendMessage(C::RED . "Please use this command in-game");
            return;
        }

        if(!$this->testPermission($sender))
            return;

        TeleportationsX::getInstance()->getSpawnManager()->setSpawn($sender->getPosition());
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}