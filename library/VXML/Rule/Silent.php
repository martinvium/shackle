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

/**
 * Silences the decorated rule, changing any failures to debug. You will
 * still be able to hook into the failure via listeners.
 * 
 * Example:
 * new Silent(new Equal(target, options))
 */
final class Silent extends DecoratorAbstract
{
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $response = $event->getResponse();
        if (! $this->rule->execute($event->getContext(), $response)) {
            $message = $response->removeByRule($this->rule);
            $response->addDebug($message['rule'], $message['debug'] . ' (silenced)');
        }
        
        return true;
    }
}