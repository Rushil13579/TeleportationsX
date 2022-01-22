<?php

namespace Rushil13579\TeleportationsX\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class TeleportAcceptCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("teleportaccept");
        $this->setDescription(DataManager::getMessage("teleport_request_accept_description"));
        $this->setUsage(DataManager::getMessage("teleport_request_accept_usage"));
        $this->setAliases(["tpa"]);
        $this->setPermission("teleportationsx.teleportaccept");
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
            $sender->sendMessage(DataManager::getMessage("teleport_request_accept_usage"));
            return;
        }

        $receiver = $sender;
        $sender = Server::getInstance()->getPlayerExact($args[0]);

        if(!$sender instanceof Player or !$sender->isOnline()) {
            $receiver->sendMessage(DataManager::getMessage("invalid_player"));
            return;
        }

        $teleportRequestManager = TeleportationsX::getInstance()->getTeleportRequestManager();

        if($teleportRequestManager->requestExists($sender, $receiver)) {
            $receiver->sendMessage(DataManager::getMessage("no_active_request"));
            return;
        }

        $receiver->teleport($sender->getPosition());

        $teleportRequestManager->closeRequest($sender, $receiver);
        $sender->sendMessage(DataManager::getMessage("sender_teleport_request_accepted", ["RECEIVER" => $receiver->getName()]));
        $receiver->sendMessage(DataManager::getMessage("receiver_teleport_request_accepted"));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return TeleportationsX::getInstance();
    }
}