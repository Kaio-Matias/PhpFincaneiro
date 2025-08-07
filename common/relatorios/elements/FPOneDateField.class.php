<?php

    class FPOneDateField extends FPElement {

        var $_size;
        
        function FPOneDateField($params)
        {
            FPElement::FPElement(&$params);
            $this->_size = $params[size];
			if (!isset($this->_size)) $this->_size = 11;
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
					' onFocus="javascript:vDateType=\'3\';"'.
					' onKeyUp="DateFormat(this,this.value,event,false,\'3\');"'.
					' onBlur="DateFormat(this,this.value,event,true,\'3\');"'.
					'>'
            ;
			echo '
			<INPUT onclick="return showCalendar(\''.$this->_name.'\', \'dd/mm/y\');" type=reset value=" ... ">';

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