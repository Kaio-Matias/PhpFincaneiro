<?php

    // Form data encoding types

    define("FP_ENCTYPE__DEFAULT", 1);
    define("FP_ENCTYPE__MULTIPART", 2);


    // Default maximum uploaded file size

    define("FP_MAX_UPLOAD_FILE_SIZE", 21048576);


    // Global form error message

    define("FP_DEFAULT_GLOBAL_FORM_ERR_MSG", 'Por favor, corrija os erros marcados!');


    // Validation error codes

    define("FP_SUCCESS", 1);
    define("FP_ERR_CODE__CUSTOM_ERROR", 2);
    define("FP_ERR_CODE__INVALID_USER_DATA", 3);
    define("FP_ERR_CODE__REQ_FIELD_IS_EMPTY", 4);
    define("FP_ERR_CODE__FIELD_IS_INVALID", 5);
    define("FP_ERR_CODE__VALUE_IS_TOO_LONG", 6);
    define("FP_ERR_CODE__FILE_UPLOAD_ATTACK", 7);
    define("FP_ERR_CODE__FILE_UPLOAD_IS_TOO_BIG", 8);
    define("FP_ERR_CODE__REQ_FILE_NOT_CHOOSEN", 9);
    define("FP_ERR_CODE__TOO_FEW_OPTS_SELECTED", 10);
    define("FP_ERR_CODE__TOO_MANY_OPTS_SELECTED", 11);

    
    // Field validation error messages

    $GLOBALS[FP_ERR_MSG] = array(
        FP_ERR_CODE__REQ_FIELD_IS_EMPTY => 'Por favor, preencha o campo &quot;[element_title]&quot;',
        FP_ERR_CODE__INVALID_USER_DATA => 'Dados inválidos para o campo (&quot;[element_title]&quot;)',
        FP_ERR_CODE__FIELD_IS_INVALID => '&quot;[element_title]&quot; valor não é válido',
        FP_ERR_CODE__VALUE_IS_TOO_LONG => '&quot;[element_title]&quot; value you entered is too long',
        FP_ERR_CODE__FILE_UPLOAD_ATTACK => '&quot;[element_title]&quot; was not uploaded correctly',
        FP_ERR_CODE__FILE_UPLOAD_IS_TOO_BIG => 'Uploaded &quot;[element_title]&quot; filesize is too big',
        FP_ERR_CODE__FILE_UPLOAD_CTYPE_NOT_ALLOWED => '&quot;[element_title]&quot; content type is not allowable',
        FP_ERR_CODE__CANT_MOVE_UPLOADED_FILE => 'Uploaded &quot;[element_title]&quot; couldn\'t be moved to destination',
        FP_ERR_CODE__REQ_FILE_NOT_CHOOSEN => 'Please, choose &quot;[element_title]&quot; for uploading',
        FP_ERR_CODE__TOO_FEW_OPTS_SELECTED => 'Selecione mais opções de &quot;[element_title]&quot;',
        FP_ERR_CODE__TOO_MANY_OPTS_SELECTED => 'Selecione menos opções de &quot;[element_title]&quot;'
    );


    // Fatal errors
    
    define("FP_FATAL_ERR__TOO_LATE_MULTIPART_SWITCH", 2);

    // Fatal error messages

    $GLOBALS[FP_FATAL_ERR_MSG_OUTPUT_TEMPL] = 
        'FormProcessor Fatal Error: [message] Script halted'
    ;
    $GLOBALS[FP_FATAL_ERR_MSG] = array(
        FP_FATAL_ERR__TOO_LATE_MULTIPART_SWITCH => 
            'You must use POST for file uploading!'
    );


    // Several useful regular expressions for user data validation

    define("FP_VALID_NAME", '/^[\w][\w\.\s\-]+$/');
    define("FP_VALID_STRICT_TITLE", '/^[\w][\w\s\-\,\:]*$/');
    define("FP_VALID_TITLE", '/^[\w][\w\.\s\-\$\!\?\,\'\(\)\:\;\/\&\@\#]*$/');
    define("FP_VALID_PASSWORD", '/^.{6,64}$/');
    // define("FP_VALID_ANYTEXT", '/^[\s\S]*$/');
    define("FP_VALID_ADDRESS", '/^[\w\.\s\-\#\,\(\)\'\"]+$/');
    define("FP_VALID_ZIP", '/^[\w\.\s\-\#\,]+$/');
    define("FP_VALID_TELEPHONE", '/^[\w\(\)\s\-]+$/');

    define("FP_VALID_QUANTITY", '/^[0-9]{1,10}$/');
    define("FP_VALID_INTEGER", '/^\-?[0-9]{1,10}$/');
    define("FP_VALID_PRICE", '/^[0-9]{1,10}(\.[0-9]{2})?$/');
    define("FP_VALID_WEIGHT", '/^[0-9]{1,10}(\.[0-9]{1,3})?$/');

    
    $__fpve_atom = '[!#\$%&\'*+\-\/=?^`{|}~\w]';
    $__fpve_word = '('.$__fpve_atom.'+)';

    define( "FP_VALID_EMAIL",
            '/^\s*'.
                '('.
                    $__fpve_word.
                    '(?:\.'.$__fpve_word.')*'.
                    '@'.
                    $__fpve_atom.'+'.
                    '(?:\.'.$__fpve_atom.'+)*'.
                    '\.[a-zA-Z]{2,3}'.
                ')'.
            '\s*$/'
    );

    unset($__fpve_atom, $__fpve_word);
?>
