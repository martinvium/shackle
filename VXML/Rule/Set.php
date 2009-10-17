<?php
namespace VXML\Rule;

use VXML;

require_once 'VXML/Rule/CompositeAbstract.php';
require_once 'VXML/Context.php';

final class Set extends CompositeAbstract
{
	public function __construct($target = null, $options = array())
	{
		if($target === null)
		{
			$target = VXML\Context::RELATIVE;
		}
		
		parent::__construct($target, $options);
	}
}