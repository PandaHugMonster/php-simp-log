<?php

namespace spaf\simputils\log\targets;

use spaf\simputils\attributes\Property;
use spaf\simputils\log\formatters\TextLogFormatter;
use spaf\simputils\log\generic\BasicLoggingTarget;
use spaf\simputils\log\LH;
use spaf\simputils\models\Box;
use spaf\simputils\models\DateTime;
use spaf\simputils\PHP;
use function fwrite;
use function is_null;
use const STDERR;
use const STDOUT;

/**
 * @property-read Box|array|null $error_levels
 */
class StandardLoggingTarget extends BasicLoggingTarget {

	#[Property(type: 'get')]
	protected $_error_levels = null;

	function __construct($error_levels = [LH::ERROR, LH::CRITICAL, LH::ALERT, LH::EMERGENCY]) {
		if (!is_null($error_levels)) {
			$this->_error_levels = PHP::box($error_levels);
		}
	}

	// FIX  LogFormatter is needed!

	function log(
		DateTime|string|null $dt,
		$level,
		\Stringable|string $message,
		array|Box|null $context = null
	): void {
		$f = new TextLogFormatter;

		$res = $f->formatData($dt, $level, $message, $context);

		if (!empty($this->_error_levels[$level])) {
			fwrite(STDERR, $res);
		} else {
			fwrite(STDOUT, $res);
		}
	}
}
