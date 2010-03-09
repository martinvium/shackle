<?php
namespace VXML\Rule;

use VXML;

require_once 'RuleAbstract.php';

final class Valid extends RuleAbstract
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
		return true;
	}
}