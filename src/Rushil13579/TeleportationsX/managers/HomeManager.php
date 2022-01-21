<?php

namespace Rushil13579\TeleportationsX\managers;

use pocketmine\Server;
use pocketmine\world\Position;
use Rushil13579\TeleportationsX\DataManager;
use Rushil13579\TeleportationsX\TeleportationsX;

class HomeManager {

    /**
     * @param string $owner
     * @param string $label
     * @param Position $position
     */
    public function addHome(string $owner, string $label, Position $position): void {
        $this->getMain()->prepare = $this->getMain()->db2->prepare("SELECT owner,label,world,x,y,z FROM homes WHERE label = :label AND owner = :owner");
        $this->getMain()->prepare->bindValue(":owner", $owner, SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
        $sql = DataManager::rowsCount();
        if(count($sql) > 0) {
            $this->getMain()->prepare = $this->getMain()->db2->prepare("UPDATE homes SET owner = :owner, label = :label, world = :world, x = :x, y = :y, z = :z WHERE label = :label");
        } else {
            $this->getMain()->prepare = $this->getMain()->db2->prepare("INSERT INTO homes (owner, label, world, x, y, z) VALUES (:owner, :label, :world, :x, :y, :z)");
        }
        $this->getMain()->prepare->bindValue(":owner", $owner, SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":world", $position->getWorld()->getDisplayName(), SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":x", $position->getFloorX(), SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":y", $position->getFloorY(), SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":z", $position->getFloorZ(), SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
    }

    /**
     * @param string $owner
     * @param string $label
     */
    public function removeHome(string $owner, string $label): void {
        $this->getMain()->prepare = $this->getMain()->db2->prepare("SELECT * FROM homes WHERE label = :label AND owner = :owner");
        $this->getMain()->prepare->bindValue(":owner", $owner, SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
        $sql = DataManager::rowsCount();
        if( count($sql) > 0 ) {
            $this->getMain()->prepare = $this->getMain()->db2->prepare("DELETE FROM homes WHERE label = :label AND owner = :owner");
            $this->getMain()->prepare->bindValue(":owner", $owner, SQLITE3_TEXT);
            $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
            $this->getMain()->result = $this->getMain()->prepare->execute();
        }
    }

    /**
     * @param string $owner
     * @param string $label
     * @return Position|null
     */
    public function getHome(string $owner, string $label): ?Position {
        $this->getMain()->prepare = $this->getMain()->db2->prepare("SELECT owner,label,x,y,z,world FROM homes WHERE label = :label AND owner = :owner");
        $this->getMain()->prepare->bindValue(":owner", $owner, SQLITE3_TEXT);
        $this->getMain()->prepare->bindValue(":label", $label, SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
        $sql = DataManager::rowsCount();
        if(count($sql) > 0){
            $sql = $sql[0];
            $world = Server::getInstance()->getWorldManager()->getWorldByName($sql["world"]);
            return new Position($sql["x"], $sql["y"], $sql["z"], $world);
        }
        return null;
    }

    /**
     * @param string $owner
     * @return array|null
     */
    public function getAllHomes(string $owner): ?array {
        $this->getMain()->prepare = $this->getMain()->db2->prepare("SELECT owner,x,y,z,world,label FROM homes WHERE owner = :owner");
        $this->getMain()->prepare->bindValue(":owner", $owner, SQLITE3_TEXT);
        $this->getMain()->result = $this->getMain()->prepare->execute();
        return DataManager::rowsCount();
    }

    /**
     * @param string $owner
     * @param string $label
     * @return bool
     */
    public function homeExists(string $owner, string $label): bool {
        return $this->getHome($owner, $label) !== null;
    }

    /**
     * @return TeleportationsX
     */
    private function getMain(): TeleportationsX {
        return TeleportationsX::getInstance();
    }

}