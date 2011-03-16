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

use Shackle\Context;
use Shackle\Event;


/**
 * Make decorated rule optional. The value will still be validated, 
 * but if value is empty, the rule will be successfull.
 * 
 * Example
 * new Rule\Optional(new Rule\Equal(target, options))
 */
final class Optional extends DecoratorAbstract
{
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $context = $event->getContext();
        $context->save();
        $context->setRelativeTarget($this->rule->getRelativeTarget());
        $values = $context->getPassedValues(Context::ALL_TARGETS);
        $context->restore();
        
        foreach ($values as $value) {
            if (! empty($value)) {
                return $this->rule->execute($context, $event->getResponse());
            }
        }
        
        $event->getResponse()->addDebug($this, 'value was optional and empty');
        $this->rule->invoke('optional', $event);
        return true;
    }
}