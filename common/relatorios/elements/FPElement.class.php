<?php

    class FPElement {
    
        var $_name;         // element name
        var $_title;
        var $_comment;      // text comment appearing under the element
        var $_value;
        var $_required;         // bool: is an element value required for submitting
        var $_valid_RE;         // regular expression for valid values
        var $_maxValueLength;   // max length of the value (optional parameter)

        var $_errCode;
        var $_customErrMsg;     // custom error message text (displayed when
                                // errCode = FP_ERR_CODE__CUSTOM_ERROR)

        var $_owner;    // reference to the parent container-class
        var $_wrapper;  // reference to a class responsible for the element displaying
        
        var $_cssStyle;  // used for <input ... style="$_cssStyle" ...>

        var $_requiredOnChecked;
            // element can become required only when a particular checkbox or radio
            // item is checked

        function FPElement($params)
        {
            $this->_name = $params[name];
            $this->_title = $params[title];
            $this->_required = $params[required] ? true : false;
            if (isset($params[valid_RE]))
                $this->_valid_RE = $params[valid_RE];
            $this->_comment = $params[comment];
            $this->_value = $params[value];
            $this->_maxValueLength = $params[max_length];

            $this->_requiredOnChecked = &$params[required_on_checked];

            if (isset($params[css_style])) $this->_cssStyle = $params[css_style];
            if (isset($params[wrapper])) $this->_wrapper = &$params[wrapper];
        }


        function &getOwner()
        {
            return $this->_owner;
        }

        function setOwner(&$containerObj)
        {
            $this->_owner = &$containerObj;
        }


        function validate()
        {
            // can be overriden
            if (isset($this->_value)  &&  $this->_value != '')
            {
                if (isset($this->_valid_RE))
                {
                    if (!preg_match($this->_valid_RE, $this->_value))
                    {
                        $this->_errCode = FP_ERR_CODE__FIELD_IS_INVALID;
                        return false;
                    }
                }

                if (isset($this->_maxValueLength)  &&  
                        strlen($this->_value) > $this->_maxValueLength)
                {
                    $this->_errCode = FP_ERR_CODE__VALUE_IS_TOO_LONG;
                    return false;
                }
            } else {
                
                if ($this->_required)
                    $_required = true;
                elseif (is_object($this->_requiredOnChecked))
                    $_required = $this->_requiredOnChecked->getValue();
                else
                    $_required = false;

                if ($_required)
                {
                    $this->_errCode = FP_ERR_CODE__REQ_FIELD_IS_EMPTY;
                    return false;
                }
            }
            $this->_errCode = FP_SUCCESS;
            return true;
        }


        function isValid()
        {
            if (!isset($this->_errCode)) $this->validate();
            return ($this->_errCode == FP_SUCCESS);
        }


        function getName() { return $this->_name; }

        function getTitle() { return $this->_title; }

        function getComment() { return $this->_comment; }

        function getValue() { return $this->_value; }

        function isValueSet() { return isset($this->_value); }

        function setValue($value)
        {
            // can be overriden
            $this->_value = $value; 
        }


        function getErrorMsg()
        {
            if (isset($this->_errCode))
            {
                $errMsg = ($this->_errCode != FP_ERR_CODE__CUSTOM_ERROR ?
                    $GLOBALS[FP_ERR_MSG][$this->_errCode] : $this->_customErrMsg
                );

                return str_replace('[element_title]', $this->_title, $errMsg);

            } else
                return '';
        }


        function invalidate($errCode, $customErrMsg = '')
        {
            if (!isset($this->_errCode) || $this->_errCode == FP_SUCCESS)
            {
                $this->_errCode = $errCode;
                if ($errCode == FP_ERR_CODE__CUSTOM_ERROR)
                    $this->_customErrMsg = $customErrMsg
                ;
            }
        }


        function getTitleSource()
        {
            return
                '<span class="'.$this->_owner->getCssClassPrefix().'Title">'.
                    ($this->_required  ||  $this->_requiredOnChecked ? 
                        '<span class="'.$this->_owner->getCssClassPrefix().'ReqTitle">' : ''
                    ).
                        $this->getTitle().
                    ($this->_required ? 
                        '</span>' : ''
                    ).
                '</span>'
            ;
        }


        function getCommentSource()
        {
            return
                '<span class="'.$this->_owner->getCssClassPrefix().'Comment">'.
                    $this->_comment .
                '</span>'
            ;
        }


        function getErrorSource()
        {
            return
                '<span class="'.$this->_owner->getCssClassPrefix().'Error">'.
                    $this->getErrorMsg().
                '</span>'
            ;
        }


        function echoSource()
        {
            // must be overriden
        }


        function display()
        {
            if (is_object($this->_wrapper))
                $this->_wrapper->display(&$this);
            else
                $this->echoSource();
        }

    }

?>