<?php

use spaf\simputils\log\LH;
use spaf\simputils\log\models\Logger;
use spaf\simputils\log\targets\StandardLoggingTarget;
use spaf\simputils\log\targets\SysLogLoggingTarget;
use spaf\simputils\log\targets\VoidLoggingTarget;
use spaf\simputils\models\Password;
use spaf\simputils\PHP;
use function spaf\simputils\basic\pd;
use function spaf\simputils\basic\pr;
use function spaf\simputils\log\basic\emergency;
use function spaf\simputils\log\basic\info;

include_once "vendor/autoload.php";

PHP::init([
	"redefinitions" => [
//		'pr' => Closure::fromCallable([LH::class, "redefPrCallback"]),
//		'pd' => Closure::fromCallable([LH::class, "redefPdCallback"]),
	]
]);

LH::init([
	['class' => StandardLoggingTarget::class],
//	[
//		'class' => VoidLoggingTarget::class,
//	],
//	new SysLogLoggingTarget(),
]);

//pr("test", 2);
//pd();

/** @var Logger $logger */
$logger = LH::$default_logger;
//
//pr($logger->targets);

emergency('Test');

info('Test');

$p = new Password(name: 'my-pass');
$p->value = 'test';

//pr("$p", $p->value, $p->type);
pr([
	'pass' => $p,
]);

pr("");


//throw new Exception('WUT');
function f1() {
	trigger_error('Something Terrible Happened');
}

function f2() {
	f1();
}

function f3() {
	f2();
}

class GG {

	static function meth1() {
		f3();
	}

	function meth2($ar1 = 22, $ar2 = 'ffff') {
		static::meth1();
	}

}

$o = new GG;

$o->meth2(69, "HHHH");

//
//LH::remap([
//	LH::NOT_SET => 'not-set',
//
//	LH::DEBUG => 'debug',
//
//	LH::INFO => 'info',
//	LH::NOTICE => 'info',
//
//	LH::WARNING => 'warn',
//
//	LH::ERROR => 'error',
//
//	LH::CRITICAL => 'critical',
//	LH::ALERT => 'critical',
//	LH::EMERGENCY => 'critical',
//]);
//
//LH::remap([
//	LH::DEBUG => 'level-0',
//	LH::INFO => 'level-1',
//	LH::NOTICE => 'level-2',
//	LH::WARNING => 'level-3',
//	LH::ERROR => 'level-4',
//	LH::CRITICAL => 'level-5',
//	LH::ALERT => 'level-6',
//	LH::EMERGENCY => 'level-7',
//]);
//
//$map = LH::inverseMap();
//
//pr($map);

//
//openlog('myScriptLog', LOG_PID | LOG_PERROR, LOG_LOCAL0);
//
//syslog(LOG_WARNING, "TEST SYS LOG");
//
//throw new Exception('WUT');
//
////$res = Logger::finalLevel('test');
//LH::log('test', 'My Message');
//
////pr($res);
//
//closelog();
