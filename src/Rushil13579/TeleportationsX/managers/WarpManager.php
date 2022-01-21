<?php

namespace Rushil13579\TeleportationsX\managers;

use pocketmine\Server;
use pocketmine\world\Position;
use Rushil13579\TeleportationsX\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class WarpManager {

    /**
     * @param string $label
     * @param Position $position
     */
    public function addWarp(string $label, Position $position): void {
        $this->getMain()->prepare = $this->getMain()->db2->prepare("SELECT label,world,x,y,z FROM warps WHERE label = :label");
        $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
        $sql = DataManager::rowsCount();
        if(count($sql) > 0) {
            $this->getMain()->prepare = $this->getMain()->db2->prepare("UPDATE warps SET label = :label, world = :world, x = :x, y = :y, z = :z WHERE label = :label");
        } else {
            $this->getMain()->prepare = $this->getMain()->db2->prepare("INSERT INTO warps (label, world, x, y, z) VALUES (:label, :world, :x, :y, :z)");
        }
        $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":world", $position->getWorld()->getDisplayName(), SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":x", $position->getFloorX(), SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":y", $position->getFloorY(), SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":z", $position->getFloorZ(), SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
    }

    /**
     * @param string $label
     */
    public function removeWarp(string $label): void {
        $this->getMain()->prepare = $this->getMain()->db2->prepare("SELECT * FROM warps WHERE label = :label");
        $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
        $sql = DataManager::rowsCount();
        if( count($sql) > 0 ) {
            $this->getMain()->prepare = $this->getMain()->db2->prepare("DELETE FROM warps WHERE label = :label");
            $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
            $this->getMain()->result = $this->getMain()->prepare->execute();
        }
    }

    /**
     * @param string $label
     * @return Position|null
     */
    public function getWarp(string $label): ?Position {
        $this->getMain()->prepare = $this->getMain()->db2->prepare("SELECT label,x,y,z,world FROM warps WHERE label = :label");
        $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
        $sql = DataManager::rowsCount();
        var_dump($sql);
        if(count($sql) > 0){
            $sql = $sql[0];
            $world = Server::getInstance()->getWorldManager()->getWorldByName($sql["world"]);
            return new Position($sql["x"], $sql["y"], $sql["z"], $world);
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getAllWarps(): ?array {
        $this->getMain()->prepare = $this->getMain()->db2->prepare("SELECT x,y,z,world,label FROM warps");
        $this->getMain()->result = $this->getMain()->prepare->execute();
        return DataManager::rowsCount();
    }

    /**
     * @param string $label
     * @return bool
     */
    public function warpExists(string $label): bool {
        return $this->getWarp($label) !== null;
    }

    /**
     * @return TeleportationsX
     */
    private function getMain(): TeleportationsX {
        return TeleportationsX::getInstance();
    }

}