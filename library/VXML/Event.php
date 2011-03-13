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
    private $rule;
    
    /**
     * @var VXML\Context
     */
    private $context;
    
    /**
     * @var VXML\Response
     */
    private $response;
    
    /**
     * @param Rule\RuleAbstract $rule
     * @param Context $context
     * @param Response $response
     */
    public function __construct(Rule\RuleAbstract $rule, Context $context, Response $response)
    {
        $this->rule = $rule;
        $this->context = $context;
        $this->response = $response;
    }
    
    /**
     * @return VXML\Rule\RuleAbstract
     */
    public function getRule()
    {
        return $this->rule;
    }
    
    /**
     * @return VXML\Context
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * @return VXML\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}