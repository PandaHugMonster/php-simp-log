<?php

namespace spaf\simputils\log\generic;

use spaf\simputils\attributes\Property;
use spaf\simputils\DT;
use spaf\simputils\generic\SimpleObject;
use spaf\simputils\models\Box;

/**
 * @property-read string $dt_format;
 */
abstract class BasicLogFormatter extends SimpleObject {

	#[Property(type: 'get')]
	protected string $_dt_format = DT::FMT_DATETIME_FULL;

	abstract function formatData(
		$dt,
		$level,
		$message,
		$context,
		string|bool $with_eol = 'auto'
	): string|Box|array;

}
