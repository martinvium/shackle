<?php
namespace VXML\Rule;

use VXML\Context;
use VXML\Event;

final class Failure extends RuleAbstract
{
	public function __construct()
	{
		parent::__construct(Context::RELATIVE);
	}
	
	/**
	 * @param Event $event
	 */
	protected function evaluate(Event $event)
	{
		$event->getResponse()->addFailure($this, 'failure rule always fails...');
		return false;
	}
}