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
namespace VXML\Rule;

use VXML\Event;
use VXML\Response;

final class Iterator extends DecoratorAbstract
{
    const NUM_VALUES = 'VXML_Rule_Iterate::NUM_VALUES';
    
    protected function initialize()
    {
        $this->setRelativeTarget($this->rule->getRelativeTarget());
        
        $this->addOption('min', self::NUM_VALUES);
        $this->addOption('max', self::NUM_VALUES);
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $results = array();
        $response = $event->getResponse();
        $values = $event->getContext()->getPassedValue();
        
        if (! is_array($values)) {
            throw new \InvalidArgumentException('target for rule Iterator, may only be an array');
        }
        
        $childResponse = new Response();
        foreach ($values as $key => $value) {
            $this->rule->setRelativeTarget($key);
            $results[] = $this->rule->execute($event->getContext(), $childResponse);
        }
        
        $numValid = count(array_filter($results));
        
        $min = ($this->getOption('min') == self::NUM_VALUES ? count($results) : $this->getOption('min'));
        if ($numValid < $min) {
            $response->merge($childResponse);
            $response->addFailure($this, 'min limit reached (valid: ' . $numValid . ', min: ' . $min . ')');
            return false;
        }
        
        $max = ($this->getOption('max') == self::NUM_VALUES ? count($results) : $this->getOption('max'));
        if ($numValid > $max) {
            $response->merge($childResponse);
            $response->addFailure($this, 'max limit reached (valid: ' . $numValid . ', max: ' . $max . ')');
            return false;
        }
        
        $childResponse->convertFailuresToDebug();
        $response->merge($childResponse);
        return true;
    }
}