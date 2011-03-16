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

use Shackle\Response;
use Shackle\Event;

final class Invert extends DecoratorAbstract
{
    /**
     * @todo Re-adding rules like this will screw up targets
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $mockResponse = new Response();
        if (! $this->rule->execute($event->getContext(), $mockResponse)) {
            foreach ($mockResponse->getFailureMessages() as $failure) {
                $mockResponse->addSuccess($failure['rule'], $failure['debug']);
            }

            return true;
        } else {
            foreach ($mockResponse->getSuccessMessages() as $success) {
                $event->getResponse()->addFailure($success['rule'], $success['debug']);
            }

            return false;
        }
    }
}