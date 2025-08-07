<?php

    class FPSubmitButton extends FPElement {

        var $_width;
        var $_caption;
        
        function FPSubmitButton($params)
        {
            FPElement::FPElement(&$params);
            $this->_size = $params[size];
            if (!isset($this->_width)) $this->_width = 16;
            $this->_caption = isset($params[caption]) ?
                $params[caption] : $this->_title;
        }


        function echoSource()
        {
            echo
                '<input type="submit"'.
                    ' name="'.$this->_name.'"'.
                    ' value="'.$this->_caption.'"'.
                    (isset($this->_cssStyle) ?
                        ' style="'.$this->_cssStyle.'"' : ''
                    ).
                    ' class="'.$this->_owner->getCssClassPrefix().'SubmitButton"'.
                '>'."\n"
            ;
        }


        function validate() {
            // ...
            $this->_errCode = FP_SUCCESS;
            return true;
        }

    }

?>