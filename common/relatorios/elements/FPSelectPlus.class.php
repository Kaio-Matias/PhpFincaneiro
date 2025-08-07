<?php

    class FPSelectPlus extends FPElement {

        var $_size;
        var $_isMultiple = false;
        var $_options = array();
        var $_selected = array();

        var $_minOptsSelection;
        var $_maxOptsSelection;
        var $_exactOptsSelection;
        
        function FPSelectPlus($params)
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
		if($selected[0] != NULL) {
	            $this->_options = is_array($selected) ? $selected : array();
		    $this->_selected = is_array($selected) ? $selected : array();
		}
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
              //      $this->_errCode = FP_ERR_CODE__INVALID_USER_DATA;
                //    return false;
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
?>
<SCRIPT LANGUAGE="JavaScript">

function small_window(myurl) {
	var newWindow;
	var props = 'scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=600,height=530';
	newWindow = window.open(myurl, "Menu", props);
}

function addToParentList(sourceList,campo) {
	destinationList = window.document.forms[0].elements[campo+'[]'];
	for(var count = destinationList.options.length - 1; count >= 0; count--) {
	destinationList.options[count] = null;
	}
	for(var i = 0; i < sourceList.options.length; i++) {
	if (sourceList.options[i] != null)
	destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
   }
}

function deleteSelectedItemsFromList(sourceList) {
var maxCnt = sourceList.options.length;
for(var i = maxCnt - 1; i >= 0; i--) {
if ((sourceList.options[i] != null) && (sourceList.options[i].selected == true)) {
		sourceList.options[i] = null;
      }
   }
}

function selectBotao<?=$this->_name?>() {

var teste = '';
srcList = window.document.forms[0].elements['<?=$this->_name?>[]'];

for(var i = 0; i < srcList.options.length; i++) { 
if (srcList.options[i] != null)
	teste = teste + srcList.options[i].text + "\"";
   }

if (teste != '')
	window.document.forms[0].<?=$this->_name?>.value = teste;
else 
	window.document.forms[0].<?=$this->_name?>.value = '';
}

</script>
<?
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
                        (in_array($title, $this->_selected) ? ' selected' : '').
                    '>'.
                        $title.
                    '</option>'."\n"
                ;
            }


           foreach ($this->_options as $value => $title)
			{
				$valor .= $title."\"";
			}

            echo
                '</select><br>'."\n".
			'<input type=button value="Adicionar" onclick = "javascript:small_window(\'relatorios/menu.php?campo='.$this->_name.'\');"> <input type=button value="Deletar" onclick = "javascript:deleteSelectedItemsFromList(elements[\''.$this->_name.'[]\']);javascript:selectBotao'.$this->_name.'();">'.
			'<input type=hidden name="'.$this->_name.'" value=\''.$valor.'\'>'."\n"."\n";
        }
    }

?>
