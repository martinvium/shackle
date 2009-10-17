<?php
namespace VXML\Rule;

require_once 'CompositeAbstract.php';

final class Import extends CompositeAbstract
{
	protected function initialize()
	{
		$this->addOption('path', null);
	}
	
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		$path = $this->getOption('path');
		
		if($path === null)
			throw new \InvalidArgumentException('path is undefined');
		
		// FIXME this should be done by some static call for autocomplete?
		$validators = $this;
		include $path;
		
		return parent::evaluate($context, $response);
	}
}