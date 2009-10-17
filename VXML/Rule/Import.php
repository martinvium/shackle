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
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 */
	protected function evaluate($context, $response)
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