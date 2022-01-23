<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class AddHomeCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("addhome");
        $this->setDescription(DataManager::getMessage("addhome_description"));
        $this->setUsage(DataManager::getMessage("addhome_usage"));
        $this->setAliases(["sethome"]);
        $this->setPermission("teleportationsx.addhome");
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
            $sender->sendMessage(TextFormat::RED . "Usage: " . DataManager::getMessage("addhome_usage"));
            return;
        }

        $name = $args[0];

        $homeManager = TeleportationsX::getInstance()->getHomeManager();

        if($homeManager->homeExists($sender->getName(), $name)) {
            $sender->sendMessage(DataManager::getMessage("home_already_exists"));
            return;
        }

        $homeManager->addhome($sender->getName(), $name, $sender->getPosition());
        $sender->sendMessage(DataManager::getMessage("home_added_successfully"));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}