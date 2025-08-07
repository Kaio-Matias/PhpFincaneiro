<?php

    class FPGroup extends FPColLayout {

        function FPGroup($params = array())
        {
            FPColLayout::FPColLayout(&$params);
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
                    ' class="'.$this->getCssClassPrefix().'GroupTbl"'.
                '>'."\n".
                '<tr>';
					
/* Alterado por Saulo Felipe (26/03/2003)
       title => 'Your Existing Account'	   no FPGroup
.
                    '<td'.
                        ' class="'.$this->getCssClassPrefix().'GroupTitleCell"'.
                    '>'.$this->_title.'</td>'.
                '</tr>'
            ;

*/
            for ($i=0; $i<$this->_elementsNum; $i++)
            {
                echo '<tr><td>'."\n";
                $this->_elements[$i]->display();
                echo '</td></tr>'."\n";
            }
            echo '</table>'."\n";
        }
    }

?>