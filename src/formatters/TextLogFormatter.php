<?php

namespace spaf\simputils\log\formatters;

use spaf\simputils\DT;
use spaf\simputils\log\generic\BasicLogFormatter;
use spaf\simputils\models\Box;
use spaf\simputils\Str;

class TextLogFormatter extends BasicLogFormatter {

	function formatData(
		$dt,
		$level,
		$message,
		$context,
		string|bool $with_eol = 'auto'
	):
	string|Box|array {
		$dt = DT::ts($dt)->getForSystemObj();
		$level = Str::upper($level);
		$res = "{$dt->format($this->_dt_format)}\t [{$level}]\t{$message}";

		// FIX  Auto EOL should make sure it's one liner or multi-liner??????
		// FIX  Auto-span to the length of the largest string - level

		if ($with_eol) {
			$res .= "\n";
		}

		return $res;
	}
}
