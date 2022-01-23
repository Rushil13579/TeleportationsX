<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as C;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class TeleportDenyCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("teleportdeny");
        $this->setDescription(DataManager::getMessage("teleport_request_deny_description"));
        $this->setUsage(DataManager::getMessage("teleport_request_deny_usage"));
        $this->setAliases(["tpd"]);
        $this->setPermission("teleportationsx.teleportdeny");
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
            $sender->sendMessage(TextFormat::RED . "Usage: " . DataManager::getMessage("teleport_request_deny_usage"));
            return;
        }

        $receiver = $sender;
        $sender = Server::getInstance()->getPlayerExact($args[0]);

        if(!$sender instanceof Player or !$sender->isOnline()) {
            $receiver->sendMessage(DataManager::getMessage("invalid_player"));
            return;
        }

        $teleportRequestManager = TeleportationsX::getInstance()->getTeleportRequestManager();

        if(!$teleportRequestManager->requestExists($sender, $receiver)) {
            $receiver->sendMessage(DataManager::getMessage("no_active_request"));
            return;
        }

        $teleportRequestManager->closeRequest($sender, $receiver);
        $sender->sendMessage(DataManager::getMessage("sender_teleport_request_denied", ["RECEIVER" => $receiver->getName()]));
        $receiver->sendMessage(DataManager::getMessage("receiver_teleport_request_denied", ["SENDER" => $sender->getName()]));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}