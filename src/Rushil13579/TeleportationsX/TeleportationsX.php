<?php

declare(strict_types=1);

namespace Rushil13579\TeleportationsX;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use Rushil13579\TeleportationsX\commands\AddHomeCommand;
use Rushil13579\TeleportationsX\commands\AddWarpCommand;
use Rushil13579\TeleportationsX\commands\DelHomeCommand;
use Rushil13579\TeleportationsX\commands\DelWarpCommand;
use Rushil13579\TeleportationsX\commands\HomeCommand;
use Rushil13579\TeleportationsX\commands\HomesCommand;
use Rushil13579\TeleportationsX\commands\SetSpawnCommand;
use Rushil13579\TeleportationsX\commands\SpawnCommand;
use Rushil13579\TeleportationsX\commands\TeleportAcceptCommand;
use Rushil13579\TeleportationsX\commands\TeleportDenyCommand;
use Rushil13579\TeleportationsX\commands\TeleportRequestCommand;
use Rushil13579\TeleportationsX\commands\WarpCommand;
use Rushil13579\TeleportationsX\commands\WarpsCommand;
use Rushil13579\TeleportationsX\managers\DataManager;
use Rushil13579\TeleportationsX\managers\HomeManager;
use Rushil13579\TeleportationsX\managers\SpawnManager;
use Rushil13579\TeleportationsX\managers\TeleportRequestManager;
use Rushil13579\TeleportationsX\managers\WarpManager;
use SQLite3;
use SQLite3Result;
use SQLite3Stmt;
use Throwable;

class TeleportationsX extends PluginBase {

    /** @var TeleportationsX */
    private static TeleportationsX $instance;
    /** @var SQLite3Stmt */
    public SQLite3Stmt $prepare;
    /** @var SQLite3Result */
    public SQLite3Result $result;
    /** @var SQLite3 */
    public SQLite3 $db2;
    /** @var SpawnManager */
    private SpawnManager $spawnManager;
    /** @var WarpManager */
    private WarpManager $warpManager;
    /** @var HomeManager */
    private HomeManager $homeManager;
    /** @var TeleportRequestManager */
    private TeleportRequestManager $teleportRequestManager;

    /**
     * @return TeleportationsX
     */
    public static function getInstance(): TeleportationsX {
        return self::$instance;
    }

    /**
     * @return SpawnManager
     */
    public function getSpawnManager(): SpawnManager {
        return $this->spawnManager;
    }

    /**
     * @return WarpManager
     */
    public function getWarpManager(): WarpManager {
        return $this->warpManager;
    }

    /**
     * @return HomeManager
     */
    public function getHomeManager(): HomeManager {
        return $this->homeManager;
    }

    /**
     * @return TeleportRequestManager
     */
    public function getTeleportRequestManager(): TeleportRequestManager {
        return $this->teleportRequestManager;
    }

    public function onEnable(): void {
        self::$instance = $this;

        $this->spawnManager = new SpawnManager();
        $this->warpManager = new WarpManager();
        $this->homeManager = new HomeManager();
        $this->teleportRequestManager = new TeleportRequestManager();

        try {
            if(!file_exists($this->getDataFolder() . "TeleportationX.db")) {
                $this->db2 = new SQLite3($this->getDataFolder() . "TeleportationX.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
            } else {
                $this->db2 = new SQLite3($this->getDataFolder() . "TeleportationX.db", SQLITE3_OPEN_READWRITE);
            }
        } catch (Throwable $error) {
            $this->getLogger()->critical($error->getMessage());
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        DataManager::init();
        $this->registerCommands();
    }

    private function registerCommands(): void {
        $commandMap = Server::getInstance()->getCommandMap();
        $commandMap->register("TeleportationsX", new SpawnCommand());
        $commandMap->register("TeleportationsX", new SetSpawnCommand());
        $commandMap->register("TeleportationsX", new WarpCommand());
        $commandMap->register("TeleportationsX", new WarpsCommand());
        $commandMap->register("TeleportationsX", new AddWarpCommand());
        $commandMap->register("TeleportationsX", new DelWarpCommand());
        $commandMap->register("TeleportationsX", new HomeCommand());
        $commandMap->register("TeleportationsX", new HomesCommand());
        $commandMap->register("TeleportationsX", new AddHomeCommand());
        $commandMap->register("TeleportationsX", new DelHomeCommand());
        $commandMap->register("TeleportationsX", new TeleportRequestCommand());
        $commandMap->register("TeleportationsX", new TeleportAcceptCommand());
        $commandMap->register("TeleportationsX", new TeleportDenyCommand());
    }

    public function onDisable(): void {
        $this->prepare->close();
    }
}