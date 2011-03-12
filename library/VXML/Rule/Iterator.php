<?php
namespace VXML\Rule;

use VXML\Event;
use VXML\Response;

final class Iterator extends DecoratorAbstract
{
	const NUM_VALUES = 'VXML_Rule_Iterate::NUM_VALUES';
	
	protected function initialize()
	{
		$this->setRelativeTarget($this->rule->getRelativeTarget());
		
		$this->addOption('min', self::NUM_VALUES);
		$this->addOption('max', self::NUM_VALUES);
	}
	
	/**
	 * @param Event $event
	 */
	protected function evaluate(Event $event)
	{
		$results = array();
		$response = $event->getResponse();
		$values = $event->getContext()->getPassedValue();
		
		if(! is_array($values))
			throw new \InvalidArgumentException('target for rule Iterator, may only be an array');
		
		$child_response = new Response();
		foreach($values as $key => $value)
		{
			$this->rule->setRelativeTarget($key);
			$results[] = $this->rule->execute($event->getContext(), $child_response);
		}
		
		$num_valid = count(array_filter($results));
		
		$min = ($this->getOption('min') == self::NUM_VALUES ? count($results) : $this->getOption('min'));
		if($num_valid < $min)
		{
			$response->merge($child_response);
			$response->addFailure($this, 'min limit reached (valid: ' . $num_valid . ', min: ' . $min . ')');
			return false;
		}
		
		$max = ($this->getOption('max') == self::NUM_VALUES ? count($results) : $this->getOption('max'));
		if($num_valid > $max)
		{
			$response->merge($child_response);
			$response->addFailure($this, 'max limit reached (valid: ' . $num_valid . ', max: ' . $max . ')');
			return false;
		}
		
		$child_response->convertFailuresToDebug();
		$response->merge($child_response);
		return true;
	}
}