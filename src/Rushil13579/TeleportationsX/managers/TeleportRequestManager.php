<?php

namespace Rushil13579\TeleportationsX\managers;

use Rushil13579\TeleportationsX\TeleportationsX;

class TeleportRequestManager {

    private array $requests = [];

    /**
     * @param string $sender
     * @param string $receiver
     */
    public function dispatchRequest(string $sender, string $receiver): void {
        $this->requests[$receiver][] = [
            $sender,
            $this->getRequestValidTill()];
    }

    /**
     * @return int
     */
    private function getRequestValidTill(): int {
        return time() + TeleportationsX::getInstance()->getConfig()->get("teleport_request_validity");
    }

    /**
     * @param string $sender
     * @param string $receiver
     */
    public function closeRequest(string $sender, string $receiver): void {
        foreach ($this->requests[$receiver] as $key => $request) {
            if($sender === $request[0]) {
                unset($this->requests[$receiver][$key]);
            }
        }
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @return bool
     */
    public function requestExists(string $sender, string $receiver): bool {
        if(isset($this->requests[$receiver])) {
            foreach ($this->requests[$receiver] as $key => $request) {
                if($sender === $request[0] and $request[1] >= time()) {
                    return true;
                } else {
                    unset($this->requests[$receiver][$key]);
                }
            }
        }
        return false;
    }

}