<?php

    class FPTextField extends FPElement {

        var $_size;
        
        function FPTextField($params)
        {
            FPElement::FPElement(&$params);
            $this->_size = $params[size];
            if (!isset($this->_size)) $this->_size = 16;
        }


        function echoSource()
        {
            echo
                '<input type="text"'.
                    ' name="'.$this->_name.'"'.
                    ' value="'.
                        htmlspecialchars($this->_value).
                    '"'.
                    ' size="'.$this->_size.'"'.
                    (isset($this->_cssStyle) ?
                        ' style="'.$this->_cssStyle.'"' : ''
                    ).
                    (isset($this->_maxValueLength) ?
                        ' maxlength="'.$this->_maxValueLength.'"' : ''
                    ).
                '>'
            ;
        }

        function getValue()
        {
            return $this->_isMultiple ? $this->_selected : $this->_selected[0];
        }

        function setValue($value)
        {
            $this->_value =
                /*htmlspecialchars(*/
                    stripslashes(
                        trim($value)
                    )
                //)    
            ;
        }
    }

?>