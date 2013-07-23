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

    public function testOutputTests()
    {

    	$x = new TimeGap\Timegap();
    				//2 days + 12 hours, 30 minutes
    	$x->setThen(time()+ (172800+43200+1800));
    	$x->setString('days, hours, minutes');
    	$x->setLimit(2);
    	$this->assertEquals($x->output(), '2 days, 12 hours');

    	$x->setLimit(1);
    	$this->assertEquals($x->output(), '2 days');

    	$x->setString('weeks, days, hours, minutes, seconds');
    	$x->setLimit(2);
    	$this->assertEquals($x->output(), '2 days, 12 hours');

    		//30 minutes;
    	$x->setThen(time()+1800);
    	$x->setString('years, months, seconds');
    	$this->assertEquals($x->output(), '1800 seconds');

    	$x->setString('minutes');
    	$this->assertEquals($x->output_default, '30 minutes');

    	//$x->setString('years');

    	$this->assertEquals($x->output_years, '');

    	$x->setThen(time()+(3600+945));
    	$this->assertEquals($x->output_hourscowsminutescowsseconds, '1 hour, 15 minutes, 45 seconds');


    }

}