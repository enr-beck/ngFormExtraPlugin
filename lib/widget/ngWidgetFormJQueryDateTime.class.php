<?php

/**
 * http://trentrichardson.com/examples/timepicker/
 */
sfContext::getInstance()->getConfiguration()->loadHelpers('JavascriptBase');

class ngWidgetFormJQueryDateTime extends sfWidgetFormInputText
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->addOption('culture', 'en');
        $this->addOption('config', array());
    }

    /**
     * Don't forget to include <?php include_javascripts_for_form($form) ?> in your template!
     */
    public function getJavascripts()
    {
        return array_merge(
            array('/js/jquery-ui-timepicker-addon.js'),
            $this->getOption('culture') == 'en' 
                ? array()
                : array(
                    '/js/jquery-ui/i18n/jquery.ui.datepicker-'.$this->getOption('culture').'.js',
                    '/js/jquery-ui/i18n/jquery.ui.timepicker-'.$this->getOption('culture').'.js'
                    )
        );
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $id = $this->generateId($name);
        $culture = $this->getOption('culture') == 'en' ? '' : $this->getOption('culture');
        $default = "{firstDay: 1, showSecond: true, dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm:ss', showOn: 'both'}";

        $config = array();
        foreach ( (array) $this->getOption('config') as $key => $val )
        {
            if ( is_bool($val) )
            {
                $config[] = "$key: " . ($val ? 'true' : 'false');
            }
            else
            {
                $config[] = "$key: '$val'";
            }
        }
        $config = '{' . implode(', ', $config) . '}';
        
        $contentTag = parent::render($name, $value, $attributes, $errors);
        $contentTag.= javascript_tag("
        $(document).ready(function(){
            if($.fn.datetimepicker) {
                $('#$id').datetimepicker($.extend({}, $.datepicker.regional['$culture'], $.timepicker.regional['$culture'], $default, $config));
            } else {
                throw 'Timepicker JS not included!';
            }
        });");

        return $contentTag;
    }

}
