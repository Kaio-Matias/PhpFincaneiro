<?php

    class FPColLayout extends FPLayout {

        var $_tblPadding = 2;
        var $_tblSpacing = 0;
        var $_tblAlign;
        var $_tblWidth;
        var $_elemAlign;

        function FPColLayout($params = array())
        {
            FPLayout::FPLayout(&$params);
            if (isset($params[table_padding])) $this->_tblPadding = $params[table_padding];
            if (isset($params[table_spacing])) $this->_tblSpacing = $params[table_spacing];
            if (isset($params[table_align])) $this->_tblAlign = $params[table_align];
            if (isset($params[table_width])) $this->_tblWidth = $params[table_width];
            if (isset($params[element_align])) $this->_elemAlign = $params[element_align];
        }

        function display()
        {
            echo 
                '<table'.
                    ' cellpadding="'.$this->_tblPadding.'"'.
                    ' cellspacing="'.$this->_tblSpacing.'"'.
                    (isset($this->_tblAlign) ?
                        ' align="'.$this->_tblAlign.'"' : ''
                    ).
                    (isset($this->_tblWidth) ?
                        ' width="'.$this->_tblWidth.'"' : ''
                    ).
                    ' border="0"'.
                '>'."\n"
            ;
            for ($i=0; $i<$this->_elementsNum; $i++)
            {
                echo
                    '<tr><td'.
                        ($this->_elemAlign ? ' align="'.$this->_elemAlign.'"' : '').
                    '>'."\n"
                ;
                $this->_elements[$i]->display();
                echo '</td></tr>'."\n";
            }
            echo
                (isset($this->_comment) ?
                '<tr>'.
                    '<td colspan="'.$this->_elementsNum.'">'.
                        $this->getCommentSource().
                    '</td>'.
                '</tr>'
            : ''
          );
          echo '</table>'."\n";
        }
    }

?>