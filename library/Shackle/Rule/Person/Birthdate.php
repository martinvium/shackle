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
namespace Shackle\Rule\Person;

use Shackle\Event;
use Shackle\Rule\CompositeAbstract;

final class Birthdate extends CompositeAbstract
{
    public function __construct($yearMonthDay, $options = array())
    {
        if (! is_array($yearMonthDay))
            throw new \InvalidArgumentException('you must specify year, month and day targets for birthdate rule');
        
        parent::__construct($yearMonthDay, $options);
    }
    
    public function initialize()
    {
        $this->setOptions(array('message' => 'birthdate test failed'));
    }
    
    /**
     * @param Event $event
     */
    public function evaluate(Event $event)
    {
        if (! parent::evaluate($event)) {
            return false;
        }
        
        $values = $event->getContext()->getPassedValues(array('year', 'month', 'day'));
        
        $datetime = date_create(sprintf("%04d%02d%02d", $values['year'], $values['month'], $values['day']));
//        $datetime->setDate($values['year'], $values['month'], $values['day']);
        
        if (! $datetime) {
            $event->getResponse()->addFailure(
                $this,
                'invalid data, unable to create valid date: '
                . '(year: ' . $values['year'] . ', month: '
                . $values['month'] . ', day: ' . $values['day'] . ')'
            );
            
            return false;
        }
        
        if ($datetime->format('Y') > 1900 && $datetime->format('Y') < 2100) {
            return true;
        }
        
        $event->getResponse()->addFailure($this, 'datetime: ' . $datetime->format('Y-m-d H:i:s'));
        return false;
    }
}