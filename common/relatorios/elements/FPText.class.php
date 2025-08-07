<?php

    class FPText extends FPElement {

        var $_text = '';
        var $_outputCallback;
        var $_outputEval;
        var $_overrideCssClass;

        function FPText($params)
        {
            FPElement::FPElement(&$params);
            $this->_text = $params[text];
            $this->_outputCallback = $params[output_callback];
            $this->_outputEval = $params[output_eval];
            $this->_overrideCssClass = $params[override_css_class];
            $this->_value = true;
            $this->_required = true;
        }

        function validate() { return true; }

        function setValue($value) { }


        function echoSource()
        {
            echo
                '<span class="'.
                    (isset($this->_overrideTextCssClass) ?
                        $this->_overrideCssClass
                     :
                        $this->_owner->getCssClassPrefix().'Text'
                    ).
                '">' .
                $this->_text
            ;
            
            if (isset($this->_outputCallback)) {
                $callbackName = $this->_outputCallback;
                $callbackName();
            }

            if (isset($this->_outputEval))
                eval($this->_outputEval);

            echo
                '</span>'
            ;
        }

    }

?>