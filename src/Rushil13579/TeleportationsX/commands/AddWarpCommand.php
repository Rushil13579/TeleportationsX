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

class AddWarpCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("addwarp");
        $this->setDescription(DataManager::getMessage("addwarp_description"));
        $this->setUsage(DataManager::getMessage("addwarp_usage"));
        $this->setAliases(["setwarp"]);
        $this->setPermission("teleportationsx.addwarp");
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
            $sender->sendMessage(TextFormat::RED . "Usage: " . DataManager::getMessage("addwarp_usage"));
            return;
        }

        $name = $args[0];

        $warpManager = TeleportationsX::getInstance()->getWarpManager();

        if($warpManager->warpExists($name)) {
            $sender->sendMessage(DataManager::getMessage("warp_already_exists"));
            return;
        }

        $warpManager->addWarp($name, $sender->getPosition());
        $sender->sendMessage(DataManager::getMessage("warp_added_successfully"));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}