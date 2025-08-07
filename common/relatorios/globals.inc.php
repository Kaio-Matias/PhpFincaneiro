<?php

    function isInstanceOf(&$obj, $className)
    {
        $className = strtolower($className);
        return get_class($obj) == $className  ||  is_subclass_of($obj, $className);
    }
    
?>