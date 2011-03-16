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
namespace Shackle;

/**
 * Rules add messages and type failure, success or debug to the response
 * which are displayed to the user in the end.
 */
final class Response
{
    /**
     * Message types
     */
    const MSG_FAILURE = 1;
    const MSG_SUCCESS = 2;
    const MSG_DEBUG   = 3;
    
    /**
     * Contains any failures, successes and debug messages added by the rules 
     * 
     * @var array
     */
    private $_messages = array();
    
    /**
     * @param Shackle\Response $response
     */
    public function merge($response)
    {
        $this->_messages = array_merge($this->_messages, $response->getAllMessages());
    }
    
    /**
     * Converts all failures on the object to debug messages
     * 
     * @deprecated should probably just use a mock response and getFailureMessages to readd as debug
     */
    public function convertFailuresToDebug()
    {
        foreach ($this->_messages as $key => $message) {
            if ($message['type'] == self::MSG_FAILURE) {
                $this->_messages[$key]['type'] = self::MSG_DEBUG;
            }
        }
    }
    
    /**
     * Add a failure message to the response 
     * 
     * @param Shackle\Rule\RuleAbstract $rule
     * @param string $debugMsg OPTIONAL
     */
    public function addFailure($rule, $debugMsg = null)
    {
        $this->addMessage(self::MSG_FAILURE, $rule, $rule->getMessage(), $debugMsg);
    }
    
    /**
     * Add a success message to the response
     * 
     * @param Shackle\Rule\RuleAbstract $rule
     * @param string $debug_msg OPTIONAL
     */
    public function addSuccess($rule, $debug_msg = null)
    {
        $this->addMessage(self::MSG_SUCCESS, $rule, null, $debug_msg);
    }
    
    /**
     * Add a debug message to the response
     * 
     * @param Shackle\Rule\RuleAbstract $rule
     * @param string $debug_msg
     */
    public function addDebug($rule, $debug_msg)
    {
        $this->addMessage(self::MSG_DEBUG, $rule, null, $debug_msg);
    }
    
    /**
     * Remove all messages added by a specific rule
     * 
     * @param Shackle\Rule\RuleAbstract $rule
     */
    public function removeByRule($rule)
    {
        foreach ($this->_messages as $key => $message) {
            if ($message['rule'] === $rule) {
                unset($this->_messages[$key]);
                return $message;
            }
        }
        
        return false;
    }
    
    /**
     * Get all messages
     * 
     * @return array
     */
    public function getAllMessages()
    {
        return $this->_messages;
    }
    
    /**
     * Get all success messages
     * 
     * @return array
     */
    public function getSuccessMessages()
    {
        return $this->getMessagesByType(self::MSG_SUCCESS);
    }
    
    /**
     * Get failure messages
     * 
     * @return array
     */
    public function getFailureMessages()
    {
        return $this->getMessagesByType(self::MSG_FAILURE);
    }
    
    /**
     * Get debug messages
     * 
     * @return array
     */
    public function getDebugMessages()
    {
        return $this->getMessagesByType(self::MSG_DEBUG);
    }
    
    /**
     * Get messages by type
     * 
     * @param string $type
     * @return array
     */
    private function getMessagesByType($type)
    {
        $messages = array();
        foreach ($this->_messages as $msg) {
            if ($msg['type'] == $type) {
                $messages[] = $msg;
            }
        }
        return $messages;
    }
    
    /**
     * Add message
     * 
     * @param integer $type
     * @param Shackle\Rule\RuleAbstract $rule
     * @param string $msg
     * @param string $debugMsg
     */
    private function addMessage($type, $rule, $msg, $debugMsg)
    {
        $this->_messages[] = array(
            'type'    => $type,
            'rule'    => $rule,
            'target'  => $rule->getResolvedTarget(),
            'message' => $msg,
            'debug'   => $debugMsg
        );
    }
}