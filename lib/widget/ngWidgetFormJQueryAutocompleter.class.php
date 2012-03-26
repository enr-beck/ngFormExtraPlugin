<?php

class ngWidgetFormJQueryAutocompleter extends sfWidgetFormInput
{
    protected function configure($options = array(), $attributes = array())
    {
        $this->addRequiredOption('url');
        $this->addOption('value_callback');
        $this->addOption('min_length', 1);
        $this->addOption('cache', false);
        $this->addOption('data_variable', 'term');
        $this->addOption('ajax_config', '{}');
        $this->addOption('json_label', 'label');
        $this->addOption('json_value', 'value');
        $this->addOption('config', '{}');

        // @TODO: what about next 2 lines??? google!!!
        // this is required as it can be used as a renderer class for sfWidgetFormChoice
        //$this->addOption('choices');

        parent::configure($options, $attributes);
    }
  
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $visibleValue = $this->getOption('value_callback') ? call_user_func($this->getOption('value_callback'), $value) : $value;
    
        return  $this->renderTag('input', array('name' => $name, 'type' => 'hidden', 'value' => $value)).
                parent::render('autocomplete_'.$name, $visibleValue, $attributes, $errors).
                sprintf(<<<EOF
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('#%s')
    .autocomplete(jQuery.extend({}, {
      minLength: %s,
      source: function(request, response){
        jQuery.ajax(jQuery.extend({}, {
          url: '%s',
          cache: %s,
          dataType: 'json',
          data: {
            %s: request.term
          },
          success: function(data){
            response(data);
          }
        }, %s));
      },
      focus: function(event, ui){
        jQuery(this).val(ui.item.%s);
        return false;
      },
      select: function(event, ui){
        jQuery(this).val(ui.item.%s);
        jQuery('#%s').val(ui.item.%s);
        return false;
      },
      change: function(event, ui){
        jQuery(this).val(ui.item ? ui.item.%s : null);
        jQuery('#%s').val(ui.item ? ui.item.%s : null);
      }
    }, %s));
  });
</script>
EOF
                    ,
                    // element selector
                    $this->generateId('autocomplete_'.$name),
                    // source option
                    $this->getOption('min_length'),
                    $this->getOption('url'),
                    $this->getOption('cache') ? 'true' : 'false',
                    $this->getOption('data_variable'),
                    $this->getOption('ajax_config'),
                    // focus option
                    $this->getOption('json_label'),
                    // select option
                    $this->getOption('json_label'),
                    $this->generateId($name), $this->getOption('json_value'),
                    // change option
                    $this->getOption('json_label'),
                    $this->generateId($name), $this->getOption('json_value'),
                    // custom config option
                    $this->getOption('config')
            );
    }
}