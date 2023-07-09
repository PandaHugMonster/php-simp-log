<?php

namespace spaf\simputils\log;

use Closure;
use spaf\simputils\log\models\Logger;
use spaf\simputils\models\Box;
use spaf\simputils\PHP;
use Throwable;
use function debug_backtrace;
use function error_get_last;
use function error_reporting;
use function json_encode;
use function print_r;
use function register_shutdown_function;
use function reset;
use function set_error_handler;
use function set_exception_handler;
use function spaf\simputils\basic\pd;
use function spaf\simputils\basic\pr;
use const E_ERROR;
use const JSON_PRETTY_PRINT;

/**
 * SimpUtils logging helper
 */
class LH {

	const NOT_SET = null;
	const DEBUG = 'debug';
	const INFO = 'info';
	const NOTICE = 'notice';
	const WARNING = 'warning';
	const ERROR = 'error';
	const CRITICAL = 'critical';
	const ALERT = 'alert';
	const EMERGENCY = 'emergency';

	private static $_map = [
		self::DEBUG => self::DEBUG,
		self::INFO => self::INFO,
		self::NOTICE => self::NOTICE,
		self::WARNING => self::WARNING,
		self::ERROR => self::ERROR,
		self::CRITICAL => self::CRITICAL,
		self::ALERT => self::ALERT,
		self::EMERGENCY => self::EMERGENCY,
	];

	static function remap(Box|array $map) {
		static::$_map = (array) $map;
	}

	static function inverseMap(): Box|array {
		$res = PHP::box();
		$map = PHP::box(static::$_map);

		foreach ($map as $key => $val) {
			if (!$res->containsKey($val)) {
				$res[$val] = $key;
			}
		}

		return $res;
	}

	static function finalLevel($level): string|int|null {
		if (!empty(static::$_map[$level])) {
			return static::$_map[$level];
		}

		return $level;
	}

	static $default_logger = null;
	static $_orig_error_reporting = null;

	static function redefPrCallback(...$args) {
		$args = json_encode($args, JSON_PRETTY_PRINT);
		static::debug("PR() | {$args}", [
			'args' => (array) $args,
		]);
	}

	static function redefPdCallback(...$args) {
		$args = json_encode($args, JSON_PRETTY_PRINT);
		static::debug("PD() | {$args}", [
			'args' => (array) $args,
		]);
		die;
	}

	static function init($targets = null, $logger_class = null) {

		if (!PHP::isClass($logger_class)) {
			// TODO Re-definitions?!
			$logger_class = Logger::class;
		}
		static::$default_logger = new $logger_class($targets);

		set_exception_handler(
			Closure::fromCallable([static::class, '_globalExceptionHandler'])
		);

		set_error_handler(
			Closure::fromCallable([static::class, '_globalErrorHandler'])
		);

		register_shutdown_function(
			Closure::fromCallable([static::class, 'shutdown'])
		);

		static::$_orig_error_reporting = $er = error_reporting();
		error_reporting(0);

		foreach (static::$default_logger->targets as $target) {
			$target->init(static::$default_logger);
		}

	}

	static function shutdown() {
		foreach (static::$default_logger->targets as $target) {
			$target->shutdown(static::$default_logger);
		}
	}

	static private function _globalErrorHandler(
		int $errno,
		string $errstr,
		string $errfile,
        int $errline,
	) {
		$trace = PHP::box(debug_backtrace());
		$trace = PHP::box($trace->unsetByKey(0)->values)->reversed();

		pd([
			'$errno' => $errno,
			'$errstr' => $errstr,
			'$errfile' => $errfile,
            '$errline' => $errline,
			'trace' => $trace
		]);

//		$error = error_get_last();
//
//		pr('test', $error);
//		if (!empty($error['type'])) {
//			$error = PHP::box($error);
//			static::error("Fatal Error: {$error}", [
//				'error' => $error,
//			]);
//		}
	}

	static private function _globalExceptionHandler(Throwable $e) {
		$trace = PHP::box($e->getTrace());
		pd($trace->reversed());

		static::error("{$e}", [
			'exception' => $e,
		]);
	}

	/**
	 * Logs with an arbitrary level
	 *
	 * @param string         $level
	 * @param string         $message
	 * @param null|Box|array $context
	 *
	 * @return void
	 */
	static function log(
		string $level,
		string $message,
		Box|array|null $context = null
	) {
		$level = static::finalLevel($level);
		$map = PHP::box(static::$_map);
		if (!$map->containsKey($level)) {
			pr("WARNING: Unknown Logging Level \"{$level}\"");
		}

		/** @var Logger $logger */
		$logger = static::$default_logger;

		$logger->log($level, $message, $context);
	}

	static function emergency($message, Box|array|null $context = null): void {
		static::log(static::EMERGENCY, $message, $context);
	}

	static function alert($message, Box|array|null $context = null): void {
		static::log(static::ALERT, $message, $context);
	}

	static function critical($message, Box|array|null $context = null): void {
		static::log(static::CRITICAL, $message, $context);
	}

	static function error($message, Box|array|null $context = null): void {
		static::log(static::ERROR, $message, $context);
	}

	static function warning($message, Box|array|null $context = null): void {
		static::log(static::WARNING, $message, $context);
	}

	static function notice($message, Box|array|null $context = null): void {
		static::log(static::NOTICE, $message, $context);
	}

	static function info($message, Box|array|null $context = null): void {
		static::log(static::INFO, $message, $context);
	}

	static function debug($message, Box|array|null $context = null): void {
		static::log(static::DEBUG, $message, $context);
	}

}
