<?php
namespace TimeGap;

class Timegap {

	var $timedata = array();

	var $timedata_indexes = array();

	var $_get_watchers = array(
		'output_' => '_parse_output',
		);

	var $_parse_indicators = array(
		'__' => '##',
		'ws' => ' ',
		'co' => ',',
		'dh' => '-',
		'_' => '',
	);

	var $index_replace_map = array();

	var $string = 'years, months, weeks, days, hours, minutes, seconds';
	
	var $limit = false;

	var $multi = array(
		'years' => 31536000,
		'months' => 2628000,
		'weeks' => 604800,
		'days' => 86400,
		'hours' => 3600,
		'minutes' => 60,
		'seconds' => 1,
	);

	function __construct($now=false, $then=false, $string=false, $limit=false)
	{	

		if($now != false)
			$this->setNow($now);

		if($then != false)
			$this->setThen($then);

		if($string != false);
			$this->setString($string);

		if($limit != false);		
			$this->setLimit($limit);

		//$this->setup_timedata(timespan($now, $then, -1));

		//$this->string = $string;

		return $this;

	}

	public static function create($now=false, $then=false, $string=false, $limit=false)
	{
		return new static($now, $then, $string, $limit);
	} 


	public static function createThen($then=false, $string=false, $limit=false)
	{
		return static::create(false, $then, $string, $limit);
	}

	public function setNow($now=false)
	{

		return $this->setDate($now,'dateNow');

	}

	public function setThen($then=false)
	{

		return $this->setDate($then,'dateThen');

	}

	private function setDate($in, $type=false)
	{
		
		$this->{$type} = !is_numeric($in) ? strtotime($in) : $in;

		return $this;

	}

	public function setString($string='')
	{

		$this->string = $string;

		return $this;

	}

	public function setLimit($limit=false)
	{

		$this->limit = $limit;

		return $this;

	}

	public function __toString()
	{

		return $this->output();

	}

	public function __isset($name)
	{

		return (bool) ($this->_is_watched_method($name) !== FALSE);

	}

	public function __get($name)
	{	
		
		if(($method = $this->_is_watched_method($name)) !== FALSE)
			return $this->{$this->_get_watchers[$method]}(str_replace($method,'', $name));
		
		return false;

	}

	private function _parse_output($string)
	{

		foreach($this->_parse_indicators as $before => $after)
			$string = str_replace($before, $after, $string);

		if($string == 'default')
			$string = false;

		return $this->output($string);

	}

	private function _is_watched_method($name)
	{
		
		foreach($this->_get_watchers as $c => $w)
		{	
			if(strpos($name, $c) !== FALSE)
			{
				return $c;
			}	
		}

		return false;

	}

	function output($string=false, $limit=false)
	{

		
		if(!$this->__build_date_data())
			return false;

		if(strpos($string, '##') !== FALSE)
			list($string, $limit) = explode('##', $string);

		if($string != FALSE)
			$this->setString($string);

		if($limit !== FALSE)
			$this->setLimit($limit);
		
		$base = 0;

		$results = array();

		$string = $this->string;


		foreach($this->timedata as $key=>$value)
		{

			if(strpos($string, $key) !== FALSE)
			{	

				$offset = $this->timedata_indexes[$key]+1;

				$plist = array_slice($this->timedata, $base, ($offset-$base), true);

				$base = $offset;

				foreach($plist as $keeper => $value)
				{	

					if($value <= 0)
						continue;

					if(!isset($results[$key]))
						$results[$key] = array();

					$results[$key][$keeper] = ($this->multi[$keeper] / $this->multi[$key]) * $value;

				}

				if(isset($results[$key]))
					$results[$key] = array_sum($results[$key]);

			}

		}

		if((bool) $this->limit === false || $this->limit > (count($results)))
			$this->limit = count($results);

		foreach(array_slice($results, 0, $this->limit) as $name => $value)
			$string = str_replace($name, "{$value} {$this->index_replace_map[$value == 1 ? substr($name,0,-1) : $name]}", $string);

		foreach(array_keys($this->multi) as $null)
			$string = trim(str_replace($null, '', $string), ",- ");

		$string = str_replace(array(', ,',' , '),array(', '), $string);
		
		foreach($this->index_replace_map as $after => $before)
			$string = str_replace($before, $after, $string);
		
		
		return $string;

	}

	private function setCacheHash($h)
	{

		$this->cacheHash = $h;

		return $this;

	}

	private function __build_date_data()
	{

		$data = $this->get_date_data();

		if(is_bool($data))
			return $data;

		foreach($this->multi as $i => $v)
			$this->timedata[$i] = (isset($data[$i]) ? $data[$i] : 0);

		$this->timedata_indexes = array_flip(array_keys($this->timedata));

		foreach($this->timedata as $key => $data)
		{ 
			$this->index_replace_map[$key] = md5(rand(0,100000).$key.$data.microtime(1));
			$this->index_replace_map[substr($key, 0, -1)] = md5(rand(0,100000).$key.$data.microtime(1));
		}

		return true;

	}

	private function get_date_data()
	{

		

		/**
			Most of below was taken from CodeIgniter's date helper timespan function
		**/

		$tempData = array();

		if($this->dateThen === FALSE)
			$this->dateThen = time();

		if($this->dateNow === FALSE)
			$this->dateNow = time();


		if($this->dateNow > $this->dateThen)
			return false;

		$range = ($this->dateThen - $this->dateNow);

		if($range === $this->cacheHash)
			return true;

		$this->setCacheHash($range);

		$years = floor($range / 31536000);

		if ($years > 0)
			$tempData['years'] = $years;
		
		$range -= $years * 31536000;

		$months = floor($range / 2628000);

		if ($years > 0 OR $months > 0)
		{
			if ($months > 0)
				$tempData['months'] = $months;

			$range -= $months * 2628000;
		}

		$weeks = floor($range / 604800);

		if ($years > 0 OR $months > 0 OR $weeks > 0)
		{
			if ($weeks > 0)
				$tempData['weeks'] = $weeks;

			$range -= $weeks * 604800;
		}			

		$days = floor($range / 86400);

		if ($months > 0 OR $weeks > 0 OR $days > 0)
		{
			if ($days > 0)
				$tempData['days'] = $days; 

			$range -= $days * 86400;
		}


		$hours = floor($range / 3600);

		if ($days > 0 OR $hours > 0)
		{
			if ($hours > 0)
				$tempData['hours'] = $hours;

			$range -= $hours * 3600;
		}

		$minutes = floor($range / 60);

		if ($days > 0 OR $hours > 0 OR $minutes > 0)
		{
			if ($minutes > 0)
				$tempData['minutes'] = $minutes;

			$range -= $minutes * 60;
		}

		//if (empty($this->timedata))
			$tempData['seconds'] = $range;

	
		return $tempData;

	}
}