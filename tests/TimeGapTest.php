<?php



include "./vendor/autoload.php";

class Tests extends PHPUnit_Framework_TestCase
{
    public function testSomething()
    {	
 		
		$x = new TimeGap\Timegap();
		$x->setNow('2013-01-01');
		$x->setThen('2013-01-05');
		$x->setString('days');
		$x->setLimit(1);

 		$this->assertEquals($x->output(), 6);      
 		
 		
    }

}