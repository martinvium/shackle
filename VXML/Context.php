<?php
namespace VXML;

/**
 * Context acts as a front for rules to the input data
 */
class Context
{
	const SEPERATOR   = '/';
	const WILDCARD 	  = '*';
	const RELATIVE    = '.';
	const PARENT	  = '..';
	const ALL_TARGETS = 'VXML_Context::ALL_ARGS';
	
	/**
	 * Input data for rules to be applied to
	 * 
	 * @var array
	 */
	private $data = array();
	
	/**
	 * Target(s) currently being processed
	 * 
	 * @var mixed
	 */
	private $resolved_targets = array();
	
	/**
	 * Stack for saving/restoring targets being processed
	 * 
	 * @var array
	 */
	private $resolved_targets_stack = array();
	
	/**
	 * @param array $data
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	/**
	 * Save current target on stack
	 * 
	 * @return void
	 */
	public function save()
	{
		$this->resolved_targets_stack[] = $this->resolved_targets;
	}
	
	/**
	 * Restore last saved target from stack
	 * 
	 * @return void
	 */
	public function restore()
	{
		$this->resolved_targets = array_pop($this->resolved_targets_stack);
	}
	
	/**
	 * Update current target relative to the previous one
	 * 
	 * @param mixed $targets
	 * @return void
	 */
	public function setRelativeTarget($targets)
	{
		if(is_scalar($targets))
		{
			$targets = array($targets);
		}
		
		if(! is_array($targets))
			throw new \InvalidArgumentException('target must be a scalar or an array');
		
		$resolved_targets = array();
		foreach($targets as $target)
		{
			if($this->isAbsolute($target))
			{
				$resolved_targets[] = self::RELATIVE . $target;
			}
			else if(count($this->resolved_targets))
			{
				foreach($this->resolved_targets as $o_target)
				{
					$resolved_targets[] = $o_target . self::SEPERATOR . $target;
				}
			}
			else
			{
				$resolved_targets[] = $target;
			}
		}
		
		$this->resolved_targets = $resolved_targets;
	}
	
	/**
	 * Get current target(s)
	 */
	public function getResolvedTarget()
	{
		return $this->resolved_targets;
	}
	
	/**
	 * Get value from context based on current target
	 */
	public function getPassedValue()
	{
		if(count($this->resolved_targets) > 1)
			throw new \InvalidArgumentException('rule only expects 1 target, multiple were given: ' . count($this->resolved_targets));
		
		return $this->getValueFromTarget(current($this->resolved_targets));
	}
	
	/**
	 * Get array of values from context based on current target, validated against 
	 * the proto passed as first parameter 
	 * 
	 * @param array $proto
	 */
	public function getPassedValues($proto)
	{
		if($proto == self::ALL_TARGETS)
		{
			$proto = $this->resolved_targets;
		}
		
		if(count($this->resolved_targets) != count($proto))
			throw new \InvalidArgumentException('number of targets mismatch');
		
		$values = array();
		foreach($proto as $key => $name)
		{
			$values[$name] = $this->getValueFromTarget($this->resolved_targets[$key]);
		}
		
		return $values;
	}
	
	/**
	 * Test if a specific target is absolute (e.g. prefixed by /)
	 * 
	 * @param string $target
	 */
	private function isAbsolute($target)
	{
		if(! is_scalar($target))
			throw new \InvalidArgumentException('can only test scalars for absoluteness');
		
		return (substr($target, 0, 1) == self::SEPERATOR);
	}
	
	/**
	 * Parse through data to find a specific targets value
	 * 
	 * @param string $target
	 */
	private function getValueFromTarget($target)
	{
		$data = $this->data;
		$pieces = explode(self::SEPERATOR, $target);
		
		foreach($pieces as $piece)
		{
			switch($piece)
			{
				case self::RELATIVE:
					continue;
				default:
					$data = (isset($data[$piece]) ? $data[$piece] : null); 	
			}
		}
		
		return $data;
	}
}