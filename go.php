<?php

include "vendor/autoload.php";

$x = new TimeGap\Timegap();

$x
	//->setNow('2013-01-01')
	->setThen(time() + 3600)
	->setString("months, weeks, days, hours")
	->setLimit(3);

echo $x->output();