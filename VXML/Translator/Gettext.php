<?php
namespace VXML\Translator;

class Gettext extends TranslatorAbstract
{
	public function translate($msg)
	{
		return _($msg);
	}
}