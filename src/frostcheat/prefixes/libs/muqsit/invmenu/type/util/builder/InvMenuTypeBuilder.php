<?php

declare(strict_types=1);

namespace frostcheat\prefixes\libs\muqsit\invmenu\type\util\builder;

use frostcheat\prefixes\libs\muqsit\invmenu\type\InvMenuType;

interface InvMenuTypeBuilder{

	public function build() : InvMenuType;
}