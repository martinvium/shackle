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