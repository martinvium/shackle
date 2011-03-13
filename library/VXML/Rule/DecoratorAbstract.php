<?php
namespace VXML\Rule;

use VXML\Rule;

abstract class DecoratorAbstract extends RuleAbstract
{
    /**
     * @var Rule
     */
    protected $rule;
    
    /**
     * @param Rule $rule
     */
    public function __construct(Rule $rule, $options = array())
    {
        $this->rule = $rule;
        
        parent::__construct('.', $options);
    }
    
    public function add($rule)
    {
        return $this->rule->add($rule);
    }
    
    public function addListener($type, $rule)
    {
        return $this->rule->addListener($type, $rule);
    }
    
    public function getMessage()
    {
        return $this->rule->getMessage();
    }
}