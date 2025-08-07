<?php

    class FPLeftTitleWrapper extends FPWrapper {
        
        var $_tblPadding = 2;
        var $_tblSpacing = 0;
        var $_tblTitleCellAlign = 'right';
		var $_tblTitleCellWidth = 150;
        var $_tblFieldCellWidth = 300;

        function FPLeftTitleWrapper($params = array())
        {
            FPWrapper::FPWrapper(&$params);

            if (isset($params[table_padding])) $this->_tblPadding = $params[table_padding];
            if (isset($params[table_spacing])) $this->_tblSpacing = $params[table_spacing];
            if (isset($params[table_title_cell_align]))
				$this->_tblTitleCellAlign = $params[table_title_cell_align];
            if (isset($params[table_title_cell_width]))
                $this->_tblTitleCellWidth = $params[table_title_cell_width];
            if (isset($params[table_field_cell_width]))
                $this->_tblFieldCellWidth = $params[table_field_cell_width];
        }


        function display(&$element)
        {
            echo
                '<table '.
                    ' cellpadding="'.$this->_tblPadding.'"'.
                    ' cellspacing="'.$this->_tblSpacing.'"'.
                    ' border="0"'.
                '>'."\n".
                (isInstanceOf($element, "FPElement") ?
                    ($element->getErrorMsg() ?
                    '<tr>'."\n".
                        '<td>&nbsp;</td>'.
                        '<td>'."\n".
                            $element->getErrorSource()."\n".
                        '</td>'."\n".
                    '</tr>'."\n"
                    :
                        ''
                    )
                  : ''
                ).
                '<tr>'."\n".
                    '<td width="'.$this->_tblTitleCellWidth.'"'.
                    (isset($this->_tblTitleCellAlign) ?
                        ' align="'.$this->_tblTitleCellAlign.'"' : ''
                    ).					
					'>'."\n".
                        $element->getTitleSource().
                    '</td>'."\n".
                    '<td'.
                        (isset($this->_tblFieldCellWidth) ?
                            ' width="'.$this->_tblFieldCellWidth.'"' : ''
                        ).
                    '>'."\n"
            ;

            $element->echoSource();

            echo
                    '</td>'."\n".
                '</tr>'."\n".
                ($element->getComment() ?
                '<tr>'."\n".
                    '<td>&nbsp;</td>'.
                    '<td'.
                        (isset($this->_tblFieldCellWidth) ?
                            ' width="'.$this->_tblFieldCellWidth.'"' : ''
                        ).
                    '>'."\n".
                        $element->getCommentSource()."\n".
                    '</td>'."\n".
                '</tr>'."\n"
                :
                    ''
                ).                
                '</table>'."\n"
            ;
        }

    }

?>