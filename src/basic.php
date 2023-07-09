<?php

namespace spaf\simputils\log\basic;


use spaf\simputils\log\LH;
use spaf\simputils\models\Box;

function emergency($message, Box|array|null $context = null): void {
	LH::emergency($message, $context);
}

function alert($message, Box|array|null $context = null): void {
	LH::alert($message, $context);
}

function critical($message, Box|array|null $context = null): void {
	LH::critical($message, $context);
}

function error($message, Box|array|null $context = null): void {
	LH::error($message, $context);
}

function warning($message, Box|array|null $context = null): void {
	LH::warning($message, $context);
}

function notice($message, Box|array|null $context = null): void {
	LH::notice($message, $context);
}

function info($message, Box|array|null $context = null): void {
	LH::info($message, $context);
}

function debug($message, Box|array|null $context = null): void {
	LH::debug($message, $context);
}
