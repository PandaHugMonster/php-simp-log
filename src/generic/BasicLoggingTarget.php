<?php

namespace spaf\simputils\log\generic;

use spaf\simputils\generic\SimpleObject;
use spaf\simputils\log\models\Logger;
use spaf\simputils\models\Box;
use spaf\simputils\models\DateTime;

abstract class BasicLoggingTarget extends SimpleObject {

	function init(Logger $logger) {

	}

	function shutdown(Logger $logger) {

	}

	abstract function log(
		DateTime|string|null $dt,
		$level,
		\Stringable|string $message,
		Box|array|null $context = null
	): void;

}
