<?php

declare(strict_types=1);

namespace Rushil13579\TeleportationsX\managers;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use Rushil13579\TeleportationsX\TeleportationsX;

class DataManager {

    public static function init() {
        TeleportationsX::getInstance()->saveDefaultConfig();
        TeleportationsX::getInstance()->saveResource("messages.yml");

        TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("SELECT * FROM sqlite_master WHERE type = 'table' AND name = 'spawn'");
        TeleportationsX::getInstance()->result = TeleportationsX::getInstance()->prepare->execute();
        $sql = DataManager::rowsCount();
        $count = count($sql);
        if($count === 0) {
            TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("CREATE TABLE spawn (
                id INTEGER PRIMARY KEY,
                x INTEGER,
                y INTEGER,
                z INTEGER,
                world TEXT)");
            TeleportationsX::getInstance()->result = TeleportationsX::getInstance()->prepare->execute();
        }

        TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("SELECT * FROM sqlite_master WHERE type = 'table' AND name = 'warps'");
        TeleportationsX::getInstance()->result = TeleportationsX::getInstance()->prepare->execute();
        $sql = DataManager::rowsCount();
        $count = count($sql);
        if($count === 0) {
            TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("CREATE TABLE warps (
                id INTEGER PRIMARY KEY,
                label TEXT,
                x INTEGER,
                y INTEGER,
                z INTEGER,
                world TEXT)");
            TeleportationsX::getInstance()->result = TeleportationsX::getInstance()->prepare->execute();
        }

        TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("SELECT * FROM sqlite_master WHERE type = 'table' AND name = 'homes'");
        TeleportationsX::getInstance()->result = TeleportationsX::getInstance()->prepare->execute();
        $sql = DataManager::rowsCount();
        $count = count($sql);
        if($count === 0) {
            TeleportationsX::getInstance()->prepare = TeleportationsX::getInstance()->db2->prepare("CREATE TABLE homes (
                id INTEGER PRIMARY KEY,
                owner TEXT,
                label TEXT,
                x INTEGER,
                y INTEGER,
                z INTEGER,
                world TEXT)");
            TeleportationsX::getInstance()->result = TeleportationsX::getInstance()->prepare->execute();
        }
    }


    /**
     * @return array
     */
    public static function rowsCount(): array {
        $row = [];

        $i = 0;

        while ($res = TeleportationsX::getInstance()->result->fetchArray(SQLITE3_ASSOC)) {

            $row[$i] = $res;
            $i++;

        }

        return $row;
    }

    /**
     * @param string $identifier
     * @param array|null $placeHolders
     * @return string
     */
    public static function getMessage(string $identifier, array $placeHolders = null): string {
        $msg = (new Config(TeleportationsX::getInstance()->getDataFolder() . "messages.yml", Config::YAML))->get($identifier);
        if($msg === null) {
            return "Error in messages, please contact server administrator";
        }
        if(is_array($placeHolders)) {
            foreach ($placeHolders as $placeHolder => $value) {
                $msg = str_replace("{" . $placeHolder . "}", (string)$value, $msg);
            }
        }
        return TextFormat::colorize($msg);
    }

}