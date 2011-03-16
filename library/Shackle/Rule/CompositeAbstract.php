<?php
/**
 * Copyright 2011 Martin Vium
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Shackle\Rule;

use Shackle\Event;
use Shackle\Response;
use Shackle\Context;

abstract class CompositeAbstract extends RuleAbstract
{
    const NUM_RULES = 'Shackle_Rule_CompositeAbstract::NUM_RULES';
    
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
        if ($numValid < $minLimit) {
            $event->getResponse()->merge($childEvents->getResponse());
            $event->getResponse()->addFailure(
                $this,
                'min limit reached (valid: ' . $numValid . ', min: ' . $minLimit . ')'
            );

            return false;
        }
        
        $maxLimit = ($this->getOption('max') == self::NUM_RULES ? $numComponents : $this->getOption('max'));
        if ($numValid > $maxLimit) {
            $event->getResponse()->merge($childEvents->getResponse());
            $event->getResponse()->addFailure(
                $this,
                'max limit reached (valid: ' . $numValid . ', max: ' . $maxLimit . ')'
            );
            return false;
        }
        
        $childEvents->getResponse()->convertFailuresToDebug();
        $event->getResponse()->merge($childEvents->getResponse());
        return true;
    }
}