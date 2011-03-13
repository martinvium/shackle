<?php
namespace VXML;

/**
 * Event is passed to rules and callbacks, when they are executed. It
 * contains the parent rule, current context updated with the rules
 * target and a response object, on which to add messages.
 */
final class Event
{
    /**
     * @var VXML\Rule\RuleAbstract
     */
    private $_rule;
    
    /**
     * @var VXML\Context
     */
    private $_context;
    
    /**
     * @var VXML\Response
     */
    private $_response;
    
    /**
     * @param Rule\RuleAbstract $rule
     * @param Context $context
     * @param Response $response
     */
    public function __construct(Rule\RuleAbstract $rule, Context $context, Response $response)
    {
        $this->_rule = $rule;
        $this->_context = $context;
        $this->_response = $response;
    }
    
    /**
     * @return VXML\Rule\RuleAbstract
     */
    public function getRule()
    {
        return $this->_rule;
    }
    
    /**
     * @return VXML\Context
     */
    public function getContext()
    {
        return $this->_context;
    }
    
    /**
     * @return VXML\Response
     */
    public function getResponse()
    {
        return $this->_response;
    }
}