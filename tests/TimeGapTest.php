<?php



include "./vendor/autoload.php";

class Tests extends PHPUnit_Framework_TestCase
{
    public function testStaticDays()
    {	
 		
		$x = new TimeGap\Timegap();
		$x->setNow('2013-01-01');
		$x->setThen('2013-01-05');
		$x->setString('days');
		$x->setLimit(1);

 		$this->assertEquals($x->output(), "4 days");      

    }

    public function testLimiting()
    {

    	$x = new Timegap\Timegap();
    				//2 days + 12 hours, 30 minutes
    	$x->setThen(time()+ (172800+43200+1800));
    	$x->setString('days, hours, minutes');
    	$x->setLimit(2);

    	$this->assertEquals($x->output(), '2 days, 12 hours');

    	$x->setLimit(1);

    	$this->assertEquals($x->output(), '2 days');

    	$x->setString('weeks, days, hours');
    	$x->setLimit(2);

    	$this->assertEquals($x->output(),'2 days, 12 hours');

    }

}