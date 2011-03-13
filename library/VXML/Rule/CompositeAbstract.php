<?php
namespace VXML\Rule;

use VXML\Event;
use VXML\Response;
use VXML\Context;

abstract class CompositeAbstract extends RuleAbstract
{
    const NUM_RULES = 'VXML_Rule_CompositeAbstract::NUM_RULES';
    
    public function __construct($target = Context::RELATIVE, $options = array())
    {
        $this->addOption('min', self::NUM_RULES);
        $this->addOption('max', self::NUM_RULES);
        
        parent::__construct($target, $options);
    }
    
    /**
     * @param string|Rule $rule
     * @return Rule
     */
    public function add($rule)
    {
        return $this->addListener('components', $rule);
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $numComponents = count($this->getListeners('components'));
        
        $childEvents = new Event($event->getRule(), $event->getContext(), new Response());
        $numValid = count(array_filter($this->invoke('components', $childEvents)));
        
        $minLimit = ($this->getOption('min') == self::NUM_RULES ? $numComponents : $this->getOption('min'));
        if($numValid < $minLimit) {
            $event->getResponse()->merge($childEvents->getResponse());
            $event->getResponse()->addFailure($this, 'min limit reached (valid: ' . $numValid . ', min: ' . $minLimit . ')');
            return false;
        }
        
        $maxLimit = ($this->getOption('max') == self::NUM_RULES ? $numComponents : $this->getOption('max'));
        if($numValid > $maxLimit) {
            $event->getResponse()->merge($childEvents->getResponse());
            $event->getResponse()->addFailure($this, 'max limit reached (valid: ' . $numValid . ', max: ' . $maxLimit . ')');
            return false;
        }
        
        $childEvents->getResponse()->convertFailuresToDebug();
        $event->getResponse()->merge($childEvents->getResponse());
        return true;
    }
}