<?php

    class FPLayout {

        var $_elements = array();
        var $_elementsNum = 0;

        var $_name;
        var $_title;
        var $_comment;
        var $_owner;

        var $_dataSource;
        var $_fileDataSource;
        var $_cssClassPrefix;

        var $_wasFormSubmitted;
// James 25/05/2008 
        var $_wasSubmitted;

        var $_wrapper;  // reference to a class responsible for the element displaying

        function FPLayout($params = array())
        {
            if (isset($params[wrapper])) $this->_wrapper = &$params[wrapper];
            if (isset($params[name])) $this->_name = $params[name];
            if (isset($params[title])) $this->_title = $params[title];
            if (isset($params[comment])) $this->_comment = $params[comment];
            if (isset($params[elements]))
                $this->addElements(&$params[elements])
            ;
        }

        function getName() { return $this->_name; }

        function addElement(&$element)
        {
            $this->_elements[$this->_elementsNum++] = &$element;
            $element->setOwner(&$this);
        }


        function addElements($elementsArray)
        {
            for ($i=0; $i<count($elementsArray); $i++)
                $this->addElement(&$elementsArray[$i])
            ;
        }


        function getSubmittedData()
        {
            $this->_dataSource = &$this->_owner->getDataSource();
            $this->_fileDataSource = &$this->_owner->getFileDataSource();

            for ($i=0; $i<$this->_elementsNum; $i++)
            {
                $element = &$this->_elements[$i];
                if (isInstanceOf($element, "FPLayout"))
                {
                    // get submitted data of all inner containers
                    $element->getSubmittedData();

                } elseif (isInstanceOf($element, "FPElement")) {

                    // get submitted data of all contained elements
                    if (isInstanceOf($element, "FPFile"))
                    {
                        $this->_switchToMultipartMode();
                        if ($this->_wasFormSubmitted())
                        {
                            $fileDataValue = $this->_fileDataSource[$element->getName()];
                            if (isset($fileDataValue))
                                $element->setValue($fileDataValue);
                        }
                    } elseif(isInstanceOf($element, "FPSelectPlus")) {
	                        if ($this->_wasFormSubmitted())
                        {
                            $dataValue = $this->_dataSource[$element->getName()];
							$dataValue = explode("\\\"",substr($dataValue,0,-2));

                            if (isset($dataValue))
                                $element->setValue($dataValue);
                            else
                                // if ($this->_wasFormSubmitted())
                                    $element->setValue(false);
                        }
                    } else {
// James 26/05/2008 // if ($this->_wasFormSubmitted())
                       if ($this->_wasFormSubmitted())
                        {
                            $dataValue = $this->_dataSource[$element->getName()];
                            if (isset($dataValue))
                                $element->setValue($dataValue);
                            else
                                // if ($this->_wasFormSubmitted())
                                    $element->setValue(false);
                        }
                    }
                }    
            }
        }


        function getElementValues()
        {
            $values = array();

            for ($i=0; $i<$this->_elementsNum; $i++)
            {
                $element = &$this->_elements[$i];
                if (isInstanceOf($element, "FPElement"))
                {
                    $name = $element->getName();
                    $value = $element->getValue();
                    if (isset($name)  &&  isset($value)  &&  $value !== false)
                        $values[$name] = $value
                    ;

                } elseif (isInstanceOf($element, "FPLayout")) {

                    $values = array_merge($values, $element->getElementValues());
                }
            }

            return $values;
        }


        function &getInnerElementByName($elmName)
        {
            for ($i=0; $i<$this->_elementsNum; $i++)
            {
                $element = &$this->_elements[$i];

                if ($element->getName() == $elmName)  return $element;
                if (isInstanceOf($element, "FPLayout")) {
                    $elm = &$element->getInnerElementByName($elmName);
                    if ($elm) return $elm;
                }
                /*
                if (isInstanceOf($element, "FPElement"))
                {
                    if ($element->getName() == $elmName)
                        return $element
                    ;
                } elseif (isInstanceOf($element, "FPLayout")) {
                    $elm = &$element->getInnerElementByName($elmName);
                    if ($elm) return $elm;
                }*/
            }
            return false;
        }


        function deleteInnerElement($elmName)
        {
            for ($i=0; $i<$this->_elementsNum; $i++)
            {
                $element = &$this->_elements[$i];
                if ($element->getName() == $elmName)
                {
                    // remove element, shift the array tail
                    $this->_elementsNum--;
                    for ($k=$i; $k<$this->_elementsNum; $k++)
                        $this->_elements[$k] = &$this->_elements[$k+1];
                    return true;
                }
                if (isInstanceOf($element, "FPLayout")  &&
                    $element->deleteInnerElement($elmName))  return true;
            }
            return false;
        }


        function validate()
        {
            $isValid = true;
            // calls validate method of all contained elements
            for ($i=0; $i<$this->_elementsNum; $i++)
            {
                if (!$this->_elements[$i]->validate())  $isValid = false;
            }
            return $isValid;
        }


        function &getOwner()
        {
            return $this->_owner;
        }


        function setOwner(&$obj)
        {
            // $obj can be an instance of either FPForm or FPLayout
            $this->_owner = &$obj;
//            $this->_cssClassPrefix = $obj->getCssClassPrefix();
        }


        function refineElementsOwner()
        {
            for ($i=0; $i<$this->_elementsNum; $i++)
            {
                $this->_elements[$i]->setOwner(&$this);
                if (isInstanceOf($this->_elements[$i], "FPLayout"))
                    $this->_elements[$i]->refineElementsOwner();
            }
        }


        // this method tells owner that FPFile object was added
        function _switchToMultipartMode()
        {
            $this->_owner->_switchToMultipartMode();
        }


        // you can call these two methods only after getSubmittedData is called
        function &getDataSource() { return $this->_dataSource; }

        function &getFileDataSource() { return $this->_fileDataSource; }

        function getCssClassPrefix() {
            if (isset($this->_cssClassPrefix))
                return $this->_cssClassPrefix;
            else
                return $this->_cssClassPrefix = $this->_owner->getCssClassPrefix();
        }
// James 26/05/2008
        function wasSubmitted() { return $this->_wasSubmitted ? true : false; }

        function _wasFormSubmitted()
        {
            return
                isset($this->_wasFormSubmitted) ?
                   $this->_wasFormSubmitted :
                   $this->_wasFormSubmitted = 
                        (isInstanceOf($this->_owner, "FPForm") ?
                            $this->_owner->_wasSubmitted() :
                            $this->_owner->_wasFormSubmitted()
                        )
            ;
        }
// James 26/05/2008 fim
        function display()
        {
            if (is_object($this->_wrapper))
                $this->_wrapper->display(&$this);
            else
                $this->echoSource();
        }


        function getTitle() { return $this->_title; }
        function getComment() { return $this->_comment; }

        function getTitleSource()
        {
            return
                '<span class="'.$this->getCssClassPrefix().'Title">'.
                    $this->getTitle().
                '</span>'
            ;
        }

        function getCommentSource()
        {
            return
                '<span class="'.$this->getCssClassPrefix().'Comment">'.
                    $this->_comment .
                '</span>'
            ;
        }

    }

?>