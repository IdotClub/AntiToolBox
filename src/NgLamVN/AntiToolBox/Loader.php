<?php

declare(strict_types=1);

namespace NgLamVN\AntiToolBox;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Loader extends PluginBase implements Listener {
	public Config $config;

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 * @priority NORMAL
	 * @ignoreCancelled true
	 */
	public function onReceive(DataPacketReceiveEvent $event) : void {
		$player = $event->getPlayer();
		$packet = $event->getPacket();

		if ($packet instanceof LoginPacket) {
			$deviceOS = (int) ($packet->clientData['DeviceOS'] ?? -1);
			$deviceModel = (string) ($packet->clientData['DeviceModel'] ?? '');
			if ($deviceOS !== DeviceOS::ANDROID) {
				return;
			}
			$name = explode(' ', $deviceModel);
			if (!isset($name[0])) {
				return;
			}
			$check = $name[0];
			$check = strtoupper($check);
			if ($check !== $name[0]) {
				$player->close("", "Join with toolbox model='$deviceModel'");
			}
		}
	}
}
