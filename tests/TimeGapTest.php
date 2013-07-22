<?php



include "./vendor/autoload.php";

class Tests extends PHPUnit_Framework_TestCase
{
    public function testSomething()
    {	
 		
		$x = new TimeGap\Timegap();

 		$this->assertEquals(102, 100);      
 		
 		
    }

}