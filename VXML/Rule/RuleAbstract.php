<?php
namespace VXML\Rule;

use VXML;

require_once 'VXML/Event.php';

abstract class RuleAbstract
{
	/**
	 * Relative target of the rule, to be applied to the context
	 * 
	 * @var array
	 */
	private $target = array();
	
	/**
	 * Last target of the rule, after it has been applied to the context
	 * WARNING: This can change for rules that are applied multiple times
	 * e.g. by Iterator
	 * 
	 * @var array
	 */
	private $resolved_target = array();
	
	/**
	 * Rules options
	 * 
	 * @var array
	 */
	private $options = array();
	
	/**
	 * Rules and callbacks listening to this rules events
	 * 
	 * @var array
	 */
	private $event_listeners = array();
	
// MAGIC
	/**
	 * @param mixed $target
	 * @param array $options OPTIONAL
	 */
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
	 * @return $rule
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
		
		$event = new VXML\Event($this, $context, $response);
		$this->invoke('pre', $event);
		
		$ret = $this->evaluate($event);
		if($ret)
		{
			$response->addSuccess($this);
			$this->invoke('valid', $event);
		}
		else 
		{
			$this->invoke('invalid', $event);
		}
		
		$this->invoke('post', $event);
		
		$context->restore();
		
		return $ret;
	}
	
	/**
	 * Get the rules failure message
	 * 
	 * @return string
	 */
	public function getMessage()
	{
		return $this->getOption('message');
	}
	
	/**
	 * Get the relative target of the rule
	 * 
	 * @return array
	 */
	public function getRelativeTarget()
	{
		return $this->target;
	}
	
	/**
	 * Set the relative target of the rule
	 * 
	 * @param string $target
	 */
	final public function setRelativeTarget($target)
	{
		$this->target = $target;
	}
	
	/**
	 * Get the last target of the rule, after it has been applied to the context
	 * WARNING: This can change for rules that are applied multiple times
	 * e.g. by Iterator
	 * 
	 * @return array
	 */
	final public function getResolvedTarget()
	{
		return $this->resolved_target;
	}
	
	/**
	 * Invoke an collection of rules based on event type
	 * 
	 * @param string $type
	 * @param VXML\Event $event
	 * @return array
	 */
	final public function invoke($type, $event)
	{
		$results = array();
		if(isset($this->event_listeners[$type]))
		{
			foreach($this->event_listeners[$type] as $event_handler)
			{
				if($event_handler instanceof RuleAbstract)
				{
					$results[] = $event_handler->execute($event->getContext(), $event->getResponse());
				}
				else if($event_handler instanceof Closure)
				{
					$results[] = (bool)$event_handler($event);
				}
				else if(is_callable($event_handler))
				{
					$results[] = (bool)call_user_func($event_handler, $event);
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
	 * @param VXML\Event $event
	 * @return boolean
	 */
	abstract protected function evaluate($event);
	
	/**
	 * Called to initialize any resources needed for the rule
	 */
	protected function initialize()
	{
		
	}
	
	/**
	 * Get option by name
	 * 
	 * @throws InvalidArgumentException if option name is unknown
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
	 * Register an option with a rule, if the default value is null, it is required
	 * to be filled out in userland.
	 * 
	 * @param string $name
	 * @param mixed $default_value
	 * @return void
	 */
	final protected function addOption($name, $default_value)
	{
		$this->options[$name] = $default_value;
	}
	
	/**
	 * Set options of a rule
	 * 
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
	 * Get all event listeners by event type
	 * 
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