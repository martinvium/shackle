<?php
namespace VXML\Rule;

use VXML;

require_once 'RuleAbstract.php';

final class Failure extends RuleAbstract
{
	public function __construct()
	{
		parent::__construct(VXML\Context::RELATIVE);
	}
	
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		$event->getResponse()->addFailure($this, 'failure rule always fails...');
		return false;
	}
}