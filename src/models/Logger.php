<?php

namespace spaf\simputils\log\models;

use Psr\Log\LoggerInterface;
use spaf\simputils\attributes\Property;
use spaf\simputils\generic\SimpleObject;
use spaf\simputils\log\exceptions\WrongTargetConfiguration;
use spaf\simputils\log\generic\BasicLoggingTarget;
use spaf\simputils\log\LH;
use spaf\simputils\models\Box;
use spaf\simputils\PHP;
use function debug_backtrace;
use function spaf\simputils\basic\now;
use function spaf\simputils\basic\pd;

/**
 *
 * @property-read Box|BasicLoggingTarget[] $targets
 */
class Logger extends SimpleObject implements LoggerInterface {

	#[Property(type: 'get')]
	protected $_targets = null;

	function __construct(Box|array $targets) {
		$final_targets = PHP::box();

		foreach ($targets as $target) {
			if (!$target instanceof BasicLoggingTarget && !PHP::isArrayCompatible($target)) {
				throw new WrongTargetConfiguration(
					'Target must be inherited from BasicLoggingTarget '.
					'or a Box/an array with target configuration'
				);
			}

			if ($target instanceof BasicLoggingTarget) {
				$final_targets->append($target);
			} else {
				if (empty($target['class']) || !PHP::isClass($target['class'])) {
					throw new WrongTargetConfiguration(
						'Target Configuration array must contain "class" value '.
						'with a valid target class.'
					);
				}
				$class = $target['class'];
				unset($target['class']);

				$final_targets->append(new $class(...$target));
			}
		}

		$this->_targets = $final_targets;
	}

	function log($level, \Stringable|string $message, Box|array|null $context = null): void {
		$now = now();
		$bt = PHP::box(debug_backtrace());
		$context['backtrace'] = $bt;
//		pd($bt);

		/** @var BasicLoggingTarget $target */
		foreach ($this->_targets as $target) {
			$target->log($now, $level, $message, $context);
		}
	}


	function emergency(\Stringable|string $message, Box|array|null $context = null): void {
		$this->log(LH::EMERGENCY, $message, $context);
	}

	function alert(\Stringable|string $message, Box|array|null $context = null): void {
		$this->log(LH::ALERT, $message, $context);
	}

	function critical(\Stringable|string $message, Box|array|null $context = null): void {
		$this->log(LH::CRITICAL, $message, $context);
	}

	function error(\Stringable|string $message, Box|array|null $context = null): void {
		$this->log(LH::ERROR, $message, $context);
	}

	function warning(\Stringable|string $message, Box|array|null $context = null): void {
		$this->log(LH::WARNING, $message, $context);
	}

	function notice(\Stringable|string $message, Box|array|null $context = null): void {
		$this->log(LH::NOTICE, $message, $context);
	}

	function info(\Stringable|string $message, Box|array|null $context = null): void {
		$this->log(LH::INFO, $message, $context);
	}

	function debug(\Stringable|string $message, Box|array|null $context = null): void {
		$this->log(LH::DEBUG, $message, $context);
	}
}
