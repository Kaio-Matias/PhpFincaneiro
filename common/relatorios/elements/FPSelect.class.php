<?php

    class FPSelect extends FPElement {

        var $_size;
        var $_isMultiple = false;
        var $_options = array();
        var $_selected = array();

        var $_minOptsSelection;
        var $_maxOptsSelection;
        var $_exactOptsSelection;
        
        function FPSelect($params)
        {
            FPElement::FPElement(&$params);
            if (isset($params[multiple]))
                $this->_isMultiple = $params[multiple] ? true : false
            ;

            $this->_size = $params[size];
            if (!isset($this->_size))
                $this->_size = $this->_isMultiple ? 5 : 1
            ;

            if (isset($params[options]))
                $this->_options = &$params[options]
            ;

            if (isset($params[selected]))
                $this->_selected = &$params[selected]
            ;

            if ($this->_isMultiple)
            {
                if (isset($params[min_options_selection]))
                    $this->_minOptsSelection = $params[min_options_selection]
                ;
                if (isset($params[max_options_selection]))
                    $this->_maxOptsSelection = $params[max_options_selection]
                ;
                if (isset($params[exact_options_selection]))
                    $this->_exactOptsSelection = $params[exact_options_selection]
                ;
                $this->_required =
                    ($this->_minOptsSelection > 0  ||  $this->_exactOptsSelection > 0)
                ;
            } else {
                $this->_required = true;
            }

        }


        function setValue($selected)
        {
            $this->_selected = is_array($selected) ? $selected : array();
        }


        function getValue()
        {
            return $this->_isMultiple ? $this->_selected : $this->_selected[0];
        }


        function validate()
        {
            $cnt = 0;
            for ($i=0; $i<count($this->_selected); $i++)
            {
                if (!isset($this->_options[$this->_selected[$i]]))
                {
                    $this->_errCode = FP_ERR_CODE__INVALID_USER_DATA;
                    return false;
                }
                $cnt++;
            }

            if ($this->_isMultiple)
            {
                if (isset($this->_minOptsSelection)  &&  $cnt < $this->_minOptsSelection)
                {
                    $this->_errCode = FP_ERR_CODE__TOO_FEW_OPTS_SELECTED;
                    return false;
                }

                if (isset($this->_maxOptsSelection)  &&  $cnt > $this->_maxOptsSelection)
                {
                    $this->_errCode = FP_ERR_CODE__TOO_MANY_OPTS_SELECTED;
                    return false;
                }

                if (isset($this->_exactOptsSelection)  &&  $cnt != $this->_exactOptsSelection)
                {
                    $this->_errCode =
                        $cnt < $this->_exactOptsSelection ?
                            FP_ERR_CODE__TOO_FEW_OPTS_SELECTED :
                            FP_ERR_CODE__TOO_MANY_OPTS_SELECTED
                    ;
                    return false;
                }
            }
            return true;
        }


        function echoSource()
        {
            echo
                '<select'.
                    ' name="'.$this->_name.'[]"'.
                    ' size="'.$this->_size.'"'.
                    ($this->_isMultiple ? ' multiple' : '').
                    (isset($this->_cssStyle) ?
                        ' style="'.$this->_cssStyle.'"' : ''
                    ).
                '>'."\n"
            ;
            foreach ($this->_options as $value => $title)
            {
                echo
                    '<option'.
                        // (!is_integer($value) ? ' value="'.$value.'"' : '').
                        ' value="'.$value.'"'.
                        (in_array($value, $this->_selected) ? ' selected' : '').
                    '>'.
                        $title.
                    '</option>'."\n"
                ;
            }

            echo
                '</select>'."\n"
            ;
        }
    }

?>