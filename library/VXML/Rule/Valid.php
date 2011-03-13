<?php
namespace VXML\Rule;

use VXML\Event;
use VXML\Context;

final class Valid extends RuleAbstract
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
        return true;
    }
}