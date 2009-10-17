<?php
namespace VXML\Translator;

require_once 'VXML/Translator/TranslatorAbstract.php';

class None extends TranslatorAbstract
{
	public function translate($msg)
	{
		return $msg;
	}
}