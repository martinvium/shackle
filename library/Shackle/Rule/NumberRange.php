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

/**
 * @todo rename to Range, shorter and number seems somewhat redundant?
 */
final class NumberRange extends RuleAbstract
{
    protected function initialize()
    {
        $this->addOption('min', 0);
        $this->addOption('max', 0);
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $value = $event->getContext()->getPassedValue();
        
        $min = $this->getOption('min');
        $max = $this->getOption('max');
        
        if (! $min && ! $max) {
            throw new \InvalidArgumentException('either min or max must be defined in rule: ' . get_class($this) . ' on target: ' . $this->getRelativeTarget());
        }
        
        if ($min && $value < $min) {
            $event->getResponse()->addFailure($this, 'minimum value for range reached (' . $value . ' < ' . $min . ')');
            return false;
        }
        
        if ($max && $value > $max) {
            $event->getResponse()->addFailure($this, 'maximum value for range reached (' . $value . ' > ' . $max . ')');
            return false;
        }
        
        return true;
    }
}