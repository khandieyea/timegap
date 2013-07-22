<?php

include "vendor/autoload.php";

// $x = new TimeGap\Timegap();

// $x
// 	//->setNow('2013-01-01')
// 	->setThen(time() + 3600)
// 	->setString("months, weeks, days, hours")
// 	->setLimit(3);

// echo $x->output();


	// $x = new TimeGap\Timegap();
 //    					//2 days + 12 hours, 30 minutes
	// $x->setThen(time()+ (172800+43200+1800));
	// $x->setString('days, hours, minutes');
	// $x->setLimit(2);

 //    // $this->assertEquals(
 //    echo '<pre>';
 //    echo $x->output();
    // echo '</pre>';

    // , '2 days, 12 hours');


$x = new TimeGap\Timegap();

//2 days + 12 hours, 30 minutes
$x->setThen(time()+ (172800+43200+1800));
$x->setString('days, hours, minutes');
$x->setLimit(2);

//$this->assertEquals(
// echo var_dump($x->output());
//, '2 days, 12 hours');


$x->setLimit(1);

//$this->assertEquals(
// echo var_dump($x->output());//, '2 days');

//$x->setString('weeks, days, hours, minutes, seconds');
$x->setLimit(2);

//$this->assertEquals(
$x->output();
//, '2 days, 12 hours');

