<?php

class Timegap {

	var $timedata = array();

	var $timedata_indexes = array();

	var $_get_watchers = array(
		'output_' => '_parse_output',
	);

	var $_parse_indicators = array(
		'ws' => ' ',
		'co' => ',',
		'dh' => '-',
	);

	var $index_replace_map = array();

	var $multi = array(
		'years' => 31536000,
		'months' => 2628000,
		'weeks' => 604800,
		'days' => 86400,
		'hours' => 3600,
		'minutes' => 60,
		'seconds' => 1,
	);

	function __construct($now, $then, $string=false)
	{	

		$this->setup_timedata(timespan($now, $then, -1));

		$this->string = $string;

		return $this;

	}

	public function __toString()
	{
		return $this->output($this->string);
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

		return $this->output($string);
	}

	private function _is_watched_method($name)
	{
		
		foreach($this->_get_watchers as $c => $w)
			if(strpos($name, $c) !== FALSE)
				return $c;

			return false;

		}

	function output($string=false, $limit=false)
	{

		  if($string === FALSE)
		   $string = $this->string;

		  if(strpos($string, '__') !== FALSE)
		   list($string, $limit) = explode('__', $string);

		  $base = 0;

		  $results = array();
		  foreach($this->timedata as $key=>$value)
		   if(strpos($string, $key) !== FALSE)
		    foreach(array_slice($this->timedata, $base, ($base = ($this->timedata_indexes[$key]+1)), true) as $keeper => $value)
		     if($value > 0)
		      $results[$key] = round(array_sum(array((isset($results[$key]) ? $results[$key] : 0), ($this->multi[$keeper] / $this->multi[$key]) * $value)));


		  if($limit === false || $limit > (count($results)))
		   $limit = count($results);

		  foreach(array_slice($results, 0, $limit) as $name => $value)
		   $string = str_replace($name, "{$value} {$this->index_replace_map[$value == 1 ? singular($name) : $name]}", $string);

		  foreach(array_keys($this->multi) as $null)
		   $string = trim(str_replace($null, '', $string), ",- ");

		  foreach($this->index_replace_map as $after => $before)
		   $string = str_replace($before, $after, $string);

		  return $string;

	}


	private function setup_timedata($data)
	{

		$this->timedata = array();

		foreach($this->multi as $i => $v)
			$this->timedata[$i] = (isset($data[$i]) ? $data[$i] : 0);

		$this->timedata_indexes = array_flip(array_keys($this->timedata));

		foreach($this->timedata as $key => $data)
		{ 
			$this->index_replace_map[$key] = md5(rand(0,100000).$key.$data.microtime(1));
			$this->index_replace_map[singular($key)] = md5(rand(0,100000).$key.$data.microtime(1));
		}
	}
}