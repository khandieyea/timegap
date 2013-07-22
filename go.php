<?php

include "vendor/autoload.php";

$x = new TimeGap\Timegap();

$x->setNow('2013-01-01')
	->setThen('2013-02-04 13:00:00')
	->setString("months, weeks, days, hours")
	->setLimit(3);

echo $x->output();