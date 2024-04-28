<?php

declare(strict_types=1);

namespace frostcheat\prefixes\libs\muqsit\simplepackethandler;

use InvalidArgumentException;
use pocketmine\event\EventPriority;
use pocketmine\plugin\Plugin;
use frostcheat\prefixes\libs\muqsit\simplepackethandler\interceptor\IPacketInterceptor;
use frostcheat\prefixes\libs\muqsit\simplepackethandler\interceptor\PacketInterceptor;
use frostcheat\prefixes\libs\muqsit\simplepackethandler\monitor\IPacketMonitor;
use frostcheat\prefixes\libs\muqsit\simplepackethandler\monitor\PacketMonitor;

final class SimplePacketHandler{

	public static function createInterceptor(Plugin $registerer, int $priority = EventPriority::NORMAL, bool $handle_cancelled = false) : IPacketInterceptor{
		if($priority === EventPriority::MONITOR){
			throw new InvalidArgumentException("Cannot intercept packets at MONITOR priority");
		}
		return new PacketInterceptor($registerer, $priority, $handle_cancelled);
	}

	public static function createMonitor(Plugin $registerer, bool $handle_cancelled = false) : IPacketMonitor{
		return new PacketMonitor($registerer, $handle_cancelled);
	}
}