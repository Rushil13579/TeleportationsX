<?php

namespace Rushil13579\TeleportationsX\managers;

use pocketmine\Server;
use pocketmine\world\Position;
use Rushil13579\TeleportationsX\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class SpawnManager {

    /**
     * @return Position
     */
    public function getSpawn(): Position {
        TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("SELECT x,y,z,world FROM spawn");
        TeleportationsX::getInstance()->result = TeleportationsX::getInstance()->prepare->execute();
        $sql = DataManager::rowsCount();
        if(count($sql) > 0) {
            $sql = $sql[0];
            $world = Server::getInstance()->getWorldManager()->getWorldByName($sql["world"]);
            return new Position($sql["x"], $sql["y"], $sql["z"], $world);
        }
        return Server::getInstance()->getWorldManager()->getDefaultWorld()->getSpawnLocation();
    }

    /**
     * @param Position $position
     */
    public function setSpawn(Position $position): void {
        TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("SELECT x,y,z,world FROM spawn");
        TeleportationsX::getInstance()->result = TeleportationsX::getInstance()->prepare->execute();
        $sql = DataManager::rowsCount();
        if(count($sql) > 0) {
            TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("UPDATE spawn SET x = :x, y = :y, z = :z, world = :world");
        } else {
            TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("INSERT INTO spawn (x, y, z, world) VALUES (:x, :y, :z, :world)");
        }
        TeleportationsX::getInstance()->prepare->bindValue(":x", $position->getFloorX(), SQLITE3_TEXT);
        TeleportationsX::getInstance()->prepare->bindValue(":y", $position->getFloorY(), SQLITE3_TEXT);
        TeleportationsX::getInstance()->prepare->bindValue(":z", $position->getFloorZ(), SQLITE3_TEXT);
        TeleportationsX::getInstance()->prepare->bindValue(":world", $position->getWorld()->getDisplayName(), SQLITE3_TEXT);
        TeleportationsX::getInstance()->prepare->execute();
    }

}