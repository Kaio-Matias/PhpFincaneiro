<?php

    class FPDateField extends FPElement {

        var $_size;
        
        function FPDateField($params)
        {
            FPElement::FPElement($params);
            $this->_size = $params[size];
            $this->_maxValueLength = $params[max_length];

			if (!isset($this->_size)) $this->_size = 11;
        }


        function echoSource()
        {
?>
<SCRIPT LANGUAGE="JavaScript">
function selectBotao<?=$this->_name?>() {
var teste = '';
srcList = window.document.forms[0].<?=$this->_name."1"?>;
srcList2 = window.document.forms[0].<?=$this->_name."2"?>;
teste = srcList.value + "\\\"" + srcList2.value ;
if (teste != '')
	window.document.forms[0].<?=$this->_name?>.value = teste;
else 
	window.document.forms[0].<?=$this->_name?>.value = '';
}
</script>

<?

			$matriz = explode("\\\"",$this->_value);

            echo
                '<input type="text"'.
					' id="'.$this->_name.'1"'.
                    ' name="'.$this->_name.'1"'.
                    ' value="'.
                    $matriz[0] .
					'"'.
                    ' size="'.$this->_size.'"'.
                    (isset($this->_cssStyle) ?
                        ' style="'.$this->_cssStyle.'"' : ''
                    ).
                    (isset($this->_maxValueLength) ?
                        ' maxlength="'.$this->_maxValueLength.'"' : ''
                    ).
					' onFocus="javascript:vDateType=\'3\';selectBotao'.$this->_name.'();"'.
					' onKeyUp="DateFormat(this,this.value,event,false,\'3\');selectBotao'.$this->_name.'();"'.
					' onBlur="DateFormat(this,this.value,event,true,\'3\');selectBotao'.$this->_name.'();"'.
					' onChange="selectBotao'.$this->_name.'();"'.
                '>'
            ;
			echo '
			<INPUT onclick="return showCalendar(\''.$this->_name.'1\', \'dd/mm/y\');" type=reset value=" ... ">';


            echo
                ' at� <input type="text"'.
					' id="'.$this->_name.'2"'.
                    ' name="'.$this->_name.'2"'.
                    ' value="'.
                    $matriz[1] .
					'"'.
                    ' size="'.$this->_size.'"'.
                    (isset($this->_cssStyle) ?
                        ' style="'.$this->_cssStyle.'"' : ''
                    ).
                    (isset($this->_maxValueLength) ?
                        ' maxlength="'.$this->_maxValueLength.'"' : ''
                    ).
					' onFocus="javascript:vDateType=\'3\';selectBotao'.$this->_name.'();"'.
					' onChange="selectBotao'.$this->_name.'();"'.
					' onKeyUp="DateFormat(this,this.value,event,false,\'3\');selectBotao'.$this->_name.'();"'.
					' onBlur="DateFormat(this,this.value,event,true,\'3\');selectBotao'.$this->_name.'();"'.
                '>'
            ;
			echo '
			<INPUT onclick="return showCalendar(\''.$this->_name.'2\', \'dd/mm/y\');" type=reset value=" ... ">';

			echo '<input type=hidden name="'.$this->_name.'" value=\''.$this->_value.'\'>';
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