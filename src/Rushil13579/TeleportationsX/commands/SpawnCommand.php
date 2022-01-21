<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;
use Rushil13579\TeleportationsX\TeleportationsX;

class SpawnCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("spawn");
        $this->setDescription("Teleport to the server spawn!");
        $this->setUsage("/spawn");
        $this->setPermission("teleportationsx.spawn");
        $this->setPermissionMessage(C::RED . "You don't have permission to use this command'");
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

        $spawnPos = TeleportationsX::getInstance()->getSpawnManager()->getSpawn();
        $sender->teleport($spawnPos);
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}