<?php

namespace spaf\simputils\log;

use spaf\simputils\models\Box;

/**
 * Static Helper class for logger
 */
class Logger {

	const NOT_SET = null;
	const DEBUG = 'debug';
	const INFO = 'info';
	const NOTICE = 'notice';
	const WARNING = 'warning';
	const ERROR = 'error';
	const CRITICAL = 'critical';
	const ALERT = 'alert';
	const EMERGENCY = 'emergency';

	/**
	 * Logs with an arbitrary level
	 *
	 * @param string         $level
	 * @param string         $message
	 * @param null|Box|array $context
	 *
	 * @return void
	 */
	function log(
		string $level,
		string $message,
		Box|array|null $context
	) {

	}

}
