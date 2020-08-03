<?php
/**
 * Example Module property
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Example Module
 * @copyright (C) 2007-2011 The Digital Development Foundation
 * @link http://xarigami.com/projects
 * @author Xarigami Team
 */
/**
 * Example Module property - Handle the textbox property
 *
 * @author mikespub <mikespub@xaraya.com>
 * @author Example Module development team
 * @package dynamicdata
 */
sys::import('modules.dynamicdata.class.properties');
class Dynamic_Example_Property extends Dynamic_Property
{
    public $id          =  1116;
    public $name        = 'example';
    public $desc        = 'Example property';
    public $layout      = 'default';
    public $tplmodule   = 'example';
    public $module      = 'example';
    public $xv_size     = 50;
    public $xv_maxlength = 254; // these attribtues might be inherited from the parent property if there is one
                                // (in fact these are common inherited properties so no need to add in getBaseValidationInfo method)
                                // If you do define your own here. Any configuration attributes start with xv_ to distinguish theme.
                                // These should be defined in getBaseValidationInfo method.

    public $xv_min      = null;
    public $xv_max      = null;

    function __construct($args)
    {
        $this->filepath   = 'modules/example/xarproperties';
        parent::__construct($args);
        /* check validation for allowed min/max length (or values) */
        if (!empty($this->validation)) {
            $this->parseValidation($this->validation);
        }
    }

    function validateValue($value = null)
    {
        if (!isset($value)) {
            $value = $this->value;
        } elseif (is_array($value)) {
            $value = serialize($value);
        }
        if (!empty($value) && strlen($value) > $this->maxlength) {
            $this->invalid = xarML('text : must be less than #(1) characters long',$this->max + 1);
            $this->value = null;
            return false;
        } elseif (isset($this->min) && strlen($value) < $this->min) {
            $this->invalid = xarML('text : must be at least #(1) characters long',$this->min);
            $this->value = null;
            return false;
        } else {
    /*  Allow HTML Here? */
            $this->value = $value;
            return true;
        }
    }

   /*   function showInput($name = '', $value = null, $size = 0, $maxlength = 0, $id = '', $tabindex = '') */
    function showInput(Array $data = array())
    {
        extract($data);

        if (empty($maxlength) && isset($this->max)) {
            $this->maxlength = $this->max;
            if ($this->size > $this->maxlength) {
                $this->size = $this->maxlength;
            }
        }
        if (empty($name)) {
            $name = 'dd_' . $this->id;
        }
        if (empty($id)) {
            $id = $name;
        }

        $data['name']     = $name;
        $data['id']       = $id;
        $data['value']    = isset($value) ? xarVarPrepForDisplay($value) : xarVarPrepForDisplay($this->value);
        $data['tabindex'] = !empty($tabindex) ? $tabindex : 0;
        $data['invalid']  = !empty($this->invalid) ? xarML('Invalid #(1)', $this->invalid) :'';
        $data['maxlength']= !empty($maxlength) ? $maxlength : $this->maxlength;
        $data['size']     = !empty($size) ? $size : $this->size;

      /* Take the showinput-example.xd template from the example module and render it */
      return xarTplProperty('example', 'example','showinput', $data);
    }

    function showOutput(Array $data = array())
    {
        extract($data);

        if (isset($value)) {
            $value=xarVarPrepHTMLDisplay($value);
        } else {
            $value=xarVarPrepHTMLDisplay($this->value);
        }
        $data['value'] = $value;

        /* Take the showoutput-example.xd template from the example module and render it */
        return xarTplProperty('example', 'example', 'showoutput', $data);

    }

    /* check validation for allowed min/max length (or values) */
    function parseValidation($validation = '')
    {
        if (is_string($validation) && strchr($validation,':')) {
            list($min,$max) = explode(':',$validation);
            if ($min !== '' && is_numeric($min)) {
                $this->min = $min; // could be int or float - cfr. FloatBox below
            }
            if ($max !== '' && is_numeric($max)) {
                $this->max = $max; // could be int or float - cfr. FloatBox below
            }
        }
    }



    /**
     * Show the current validation rule in a specific form for this property type
     *
     * @param $args['name'] name of the field (default is 'dd_NN' with NN the property id)
     * @param $args['validation'] validation rule (default is the current validation)
     * @param $args['id'] id of the field
     * @param $args['tabindex'] tab index of the field
     * @return string containing the HTML (or other) text to output in the BL template
     */
    function showValidation(Array $args = array())
    {
        extract($args);

        $data = array();
        $data['name']       = !empty($name) ? $name : 'dd_'.$this->id;
        $data['id']         = !empty($id)   ? $id   : 'dd_'.$this->id;
        $data['tabindex']   = !empty($tabindex) ? $tabindex : 0;
        $data['invalid']    = !empty($this->invalid) ? xarML('Invalid #(1)', $this->invalid) :'';

        if (isset($validation)) {
            $this->validation = $validation;
            // check validation for allowed min/max length (or values)
            $this->parseValidation($validation);
        }
        $data['min'] = isset($this->min) ? $this->min : '';
        $data['max'] = isset($this->max) ? $this->max : '';
        $data['other'] = '';
        // if we didn't match the above format
        if (!isset($this->min) && !isset($this->max)) {
            $data['other'] = xarVarPrepForDisplay($this->validation);
        }

        /* allow template override by child classes */
        if (!isset($template)) {
            $template = 'example';
        }
        /* Take the example-validation.xd template from the example module and render it */
        return xarTplProperty('example', $template, 'validation', $data);
    }

    /**
     * Update the current validation rule in a specific way for each property type
     *
     * @param $args['name'] name of the field (default is 'dd_NN' with NN the property id)
     * @param $args['validation'] new validation rule
     * @param $args['id'] id of the field
     * @return bool true if the validation rule could be processed, false otherwise
     */
     function updateValidation(Array $args = array())
     {
         extract($args);

         /* in case we need to process additional input fields based on the name */
         if (empty($name)) {
             $name = 'dd_'.$this->id;
         }

         /* do something with the validation and save it in $this->validation */
         if (isset($validation)) {
             if (is_array($validation)) {
                 if (isset($validation['min']) && $validation['min'] !== '' && is_numeric($validation['min'])) {
                     $min = $validation['min'];
                 } else {
                     $min = '';
                 }
                 if (isset($validation['max']) && $validation['max'] !== '' && is_numeric($validation['max'])) {
                     $max = $validation['max'];
                 } else {
                     $max = '';
                 }
                 // we have some minimum and/or maximum length
                 if ($min !== '' || $max !== '') {
                     $this->validation = $min .':'. $max;

                 // we have some other rule
                 } elseif (!empty($validation['other'])) {
                     $this->validation = $validation['other'];

                 } else {
                     $this->validation = '';
                 }
             } else {
                 $this->validation = $validation;
             }
         }

         /*tell the calling function that everything is OK */
         return true;
     }

    /* This function returns a serialized array of validation options specific for this property
     * The validation options will be combined with global validation options so only specific should be defined here
     * These validation options can be inherited  if necesary
     */
    function getBaseValidationInfo()
    {
        static $validationarray = array();
        if (empty($validationarray)) {
            $parentvals = parent::getBaseValidationInfo();
            //if you want to define your own attributes, then do so here. Check they are not already defined in parent property
            $validations= array(   'xv_min'    =>  array('label'=>xarML('Minimum length'),          //A label shown in the configuration screen
                                                          'description'=>xarML('Minimum required length of this field'), //Used for tool tip
                                                          'propertyname'=>'integerbox', //the type of attribute this should be
                                                          'ignore_empty'  =>1,          // whether to allow empty
                                                          'ctype'=>'validation',        //a category of this particular attribute
                                                          'propargs'=> array(),         //an array of configuration options for this attribute if relevant
                                                          ),
                                        'xv_max'    =>  array('label'=>xarML('Maximum length'),
                                                          'description'=>xarML('Maximum required length of this field'),
                                                          'propertyname'=>'integerbox',
                                                          'ignore_empty'  =>1,
                                                          'ctype'=>'validation'
                                                           ),
                                        'xv_size'          =>  array('label'=>xarML('Maximum input display'),
                                                          'description'=>xarML('Maximum number of characters visible in the input box'),
                                                          'propertyname'=>'integerbox',
                                                          'ignore_empty'  =>1,
                                                          'ctype'=>'display',
                                                        ),
                                    );
             $validationarray= array_merge($validations,$parentvals);
        }
        return $validationarray;
    }
    /**
     * Get the base information for this property.
     *
     * @return array Base information for this property
     **/
     function getBasePropertyInfo()
     {
         $args = array();
         $baseInfo = array(
                              'id'         => 1116,
                              'name'       => 'example',
                              'label'      => 'Example property',
                              'format'     => '1116',
                              'validation' => '',
                              'source'     => '',
                              'dependancies' => '',
                              'requiresmodule' => 'example',
                              'filepath'    => 'modules/example/xarproperties',
                              'aliases' => '',
                              'args'       => serialize( $args ),
                            /* ... */
                           );
        return $baseInfo;
    }
}
?>