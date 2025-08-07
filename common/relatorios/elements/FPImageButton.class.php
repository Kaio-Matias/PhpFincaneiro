<?php

    class FPImageButton extends FPElement {

        var $_width;
        var $_image;
        var $_action;
        
        function FPImageButton($params)
        {
            FPElement::FPElement(&$params);
            $this->_size = $params[size];   if (!isset($this->_width)) $this->_width = 16;
            $this->_image = isset($params[src]) ?  $params[src] : $this->_image = "salvar.gif";
            $this->_action = isset($params[act]) ?  $params[act] : $this->_action = "";
        }


        function echoSource()
        {
            echo 
                '<input type="image"'.
                    ' name="'.$this->_name.'"'.
                    ' src="../images/'.$this->_image.'"'.
					' onclick="'.$this->_action.'"'.
                    (isset($this->_cssStyle) ? ' style="'.$this->_cssStyle.'"' : '').
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