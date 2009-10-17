<?php
namespace VXML\Rule;

abstract class RuleAbstract
{
	private $target = array();
	
	private $resolved_target = array();
	
	private $options = array();
	
	private $event_listeners = array();
	
// MAGIC
	public function __construct($target, $options = array())
	{
		$this->setRelativeTarget($target);
		$this->addOption('message', false);
		$this->initialize();
		
		$this->setOptions($options);
	}
	
// PUBLIC
	/**
	 * To be implemented by a composite rule
	 * 
	 * @param VXML\Rule\RuleAbstract $rule
	 * @return VXML\Rule\RuleAbstract
	 */
	public function add($rule)
	{
		throw new \Exception('not supported');
	}
	
	/**
	 * Add a rule or callback to an event
	 * 
	 * @param string $event
	 * @param string|VXML\Rule\RuleAbstract $rule
	 */
	public function addListener($event, $rule)
	{
		return $this->event_listeners[$event][] = $rule;
	}
	
	/**
	 * Evaluate rule and invoke events and setup context
	 * 
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 * @return boolean
	 */
	public function execute($context, $response)
	{
		$context->save();
		$context->setRelativeTarget($this->getRelativeTarget());
		$this->resolved_target = $context->getResolvedTarget();
		
		$this->invoke('pre', $context, $response);
		
		$ret = $this->evaluate($context, $response);
		if($ret)
		{
			$response->addSuccess($this);
			$this->invoke('valid', $context, $response);
		}
		else 
		{
			$this->invoke('invalid', $context, $response);
		}
		
		$this->invoke('post', $context, $response);
		
		$context->restore();
		
		return $ret;
	}
	
	public function getMessage()
	{
		return $this->getOption('message');
	}
	
	/**
	 * @return string
	 */
	public function getRelativeTarget()
	{
		return $this->target;
	}
	
	/**
	 * @param string $target
	 */
	final public function setRelativeTarget($target)
	{
		$this->target = $target;
	}
	
	public function getResolvedTarget()
	{
		return $this->resolved_target;
	}
	
	/**
	 * Invoke an collection of rules based on event type
	 * 
	 * @param string $event
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 * @return array
	 */
	final public function invoke($type, $context, $response)
	{
		$results = array();
		if(isset($this->event_listeners[$type]))
		{
			foreach($this->event_listeners[$type] as $event_handler)
			{
				if($event_handler instanceof RuleAbstract)
				{
					$results[] = $event_handler->execute($context, $response);
				}
				else if($event_handler instanceof Closure)
				{
					$results[] = (bool)$event_handler($context, $response);
				}
				else if(is_callable($event_handler))
				{
					$results[] = (bool)call_user_func_array($event_handler, array($context, $response));
				}
				else
				{
					throw new \InvalidArgumentException('invalid event type: ' . var_export($event_handler, true));
				}
			}
		}
		
		return $results;
	}
	
// PROTECTED
	/**
	 * Evaluate the rule and add any messages to the response object
	 * 
	 * @param VXML_Context $context
	 * @param VXML_Response $response
	 * @return boolean
	 */
	abstract protected function evaluate($context, $response);
	
	/**
	 * Called to initialize any resources needed for the rule
	 */
	protected function initialize()
	{
		
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	final protected function getOption($name)
	{
		if($this->options[$name] === null)
			throw new \InvalidArgumentException('option "' . $name . '" is required, but was left undefined in rule: ' . get_class($this) . ' on target: ' . $this->getRelativeTarget());
		
		return $this->options[$name];
	}
	
	/**
	 * @param string $name
	 * @param mixed $default_value
	 * @return void
	 */
	final protected function addOption($name, $default_value)
	{
		$this->options[$name] = $default_value;
	}
	
	/**
	 * @param array $options
	 * @return void
	 */
	final protected function setOptions($options)
	{
		if(! is_array($options))
			throw new \InvalidArgumentException('options must be an array in rule: ' . get_class($this) . ' on target: ' . $this->getRelativeTarget());
		
		foreach($options as $name => $value)
		{
			if(! array_key_exists($name, $this->options))
				throw new \InvalidArgumentException('option "' . $name . '" does not exist');
			
			$this->options[$name] = $value;
		}
	}
	
	/**
	 * @param string $type
	 * @return array
	 */
	final protected function getListeners($type)
	{
		if(! array_key_exists($type, $this->event_listeners))
		{
			return array();
		}
		
		return $this->event_listeners[$type];
	}
}