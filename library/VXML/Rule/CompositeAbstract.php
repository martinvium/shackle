<?php
namespace VXML\Rule;

use VXML\Event;
use VXML\Response;
use VXML\Context;

abstract class CompositeAbstract extends RuleAbstract
{
	const NUM_RULES = 'VXML_Rule_CompositeAbstract::NUM_RULES';
	
	public function __construct($target = Context::RELATIVE, $options = array())
	{
		$this->addOption('min', self::NUM_RULES);
		$this->addOption('max', self::NUM_RULES);
		
		parent::__construct($target, $options);
	}
	
	/**
	 * @param string|Rule $rule
	 * @return Rule
	 */
	public function add($rule)
	{
		return $this->addListener('components', $rule);
	}
	
	/**
	 * @param Event $event
	 */
	protected function evaluate(Event $event)
	{
		$num_components = count($this->getListeners('components'));
		
		$child_event = new Event($event->getRule(), $event->getContext(), new Response());
		$num_valid = count(array_filter($this->invoke('components', $child_event)));
		
		$min_limit = ($this->getOption('min') == self::NUM_RULES ? $num_components : $this->getOption('min'));
		if($num_valid < $min_limit)
		{
			$event->getResponse()->merge($child_event->getResponse());
			$event->getResponse()->addFailure($this, 'min limit reached (valid: ' . $num_valid . ', min: ' . $min_limit . ')');
			return false;
		}
		
		$max_limit = ($this->getOption('max') == self::NUM_RULES ? $num_components : $this->getOption('max'));
		if($num_valid > $max_limit)
		{
			$event->getResponse()->merge($child_event->getResponse());
			$event->getResponse()->addFailure($this, 'max limit reached (valid: ' . $num_valid . ', max: ' . $max_limit . ')');
			return false;
		}
		
		$child_event->getResponse()->convertFailuresToDebug();
		$event->getResponse()->merge($child_event->getResponse());
		return true;
	}
}