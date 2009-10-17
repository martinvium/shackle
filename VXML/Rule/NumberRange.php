<?php
namespace VXML\Rule;

require_once 'RuleAbstract.php';

final class NumberRange extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('min', 0);
		$this->addOption('max', 0);
	}
	
	/**
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 */
	protected function evaluate($context, $response)
	{
		$value = $context->getPassedValue();
		
		$min = $this->getOption('min');
		if($min && $value < $min)
		{
			$response->addFailure($this, 'minimum value for range reached (' . $value . ' < ' . $min . ')');
			return false;
		}
		
		$max = $this->getOption('max');
		if($max && $value > $max)
		{
			$response->addFailure($this, 'maximum value for range reached (' . $value . ' > ' . $max . ')');
			return false;
		}
		
		return true;
	}
}