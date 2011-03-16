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

final class StringLength extends RuleAbstract
{
    const MAX_LENGTH = 'Shackle_Rule_StringLength::MAX_LENGTH';
    
    protected function initialize()
    {
        $this->addOption('min', 0);
        $this->addOption('max', self::MAX_LENGTH);
        $this->addOption('charset', 'utf-8');
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $value = $event->getContext()->getPassedValue();
        $strLength = iconv_strlen($value, $this->getOption('charset'));
        
        $min = $this->getOption('min');
        $max = $this->getOption('max');
        
        if(! $min && $max == self::MAX_LENGTH)
            throw new \InvalidArgumentException('either min or max must be defined in rule: ' . get_class($this) . ' on target: ' . $this->getRelativeTarget());
        
        if($strLength < $min) {
            $event->getResponse()->addFailure($this, 'string to short (' . $min . '<' . $strLength . ')');
            return false;
        }
        
        if($max === self::MAX_LENGTH) {
            $max = $strLength;
        }
        
        if($strLength > $max) {
            $event->getResponse()->addFailure($this, 'string to long (' . $min . '>' . $strLength . ')');
            return false;
        }
        
        return true;
    }
}