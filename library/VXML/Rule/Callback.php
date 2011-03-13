<?php
namespace VXML\Rule;

use VXML\Event;

final class Callback extends RuleAbstract
{
    protected function initialize()
    {
        $this->addOption('callback', null);
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $callback = $this->getOption('callback');
        
        $this->addListener('callback', $callback);
        if (count(array_filter($this->invoke('callback', $event))) == 1) {
            return true;
        }
        
        $event->getResponse()->addFailure($this);
        return false;
    }
}