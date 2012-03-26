<?php

class ngWidgetFormDoctrineJQueryAutocompleter extends ngWidgetFormJQueryAutocompleter
{
    public function __construct($options = array(), $attributes = array())
    {
        $options['value_callback'] = array($this, 'toString');
        $options['json_value'] = 'id';
        
        parent::__construct($options, $attributes);
    }
    
    protected function configure($options = array(), $attributes = array())
    {
        $this->addRequiredOption('model');
        $this->addOption('method_for_query', 'findOneById');
        $this->addOption('method', '__toString');
        
        parent::configure($options, $attributes);
    }
    
    protected function toString($value)
    {
        $object = null;
        
        if ($value != null)
        {
            $class = Doctrine::getTable($this->getOption('model'));
            $method = $this->getOption('method_for_query');
            
            $object = call_user_func(array($class, $method), $value);
        }
        
        $method = $this->getOption('method');
        
        if (!method_exists($this->getOption('model'), $method))
        {
            throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $method, __CLASS__));
        }
        
        return !is_null($object) ? $object->$method() : '';
    }
}