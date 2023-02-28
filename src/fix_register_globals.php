<?php

if (!function_exists('register_globals')) {
    function register_globals($order = 'egpcns')
    {
        // define a subroutine
        if (!function_exists('register_global_array')) {

            function register_global_array(array $superglobal)
            {
                foreach ($superglobal as $varname => $value) {
                    global $$varname;
                    $$varname = $value;
                }
            }

        }

        $order = explode("\r\n", trim(chunk_split($order, 1)));
        foreach ($order as $k) {
            switch (strtolower($k)) {
                case 'e':
                    register_global_array($_ENV);
                    break;
                case 'g':
                    register_global_array($_GET);
                    break;
                case 'p':
                    register_global_array($_POST);
                    break;
                case 'c':
                    register_global_array($_COOKIE);
                    break;
                case 'n':
                    register_global_array($_SESSION);
                    break;
                case 's':
                    register_global_array($_SERVER);
                    break;
            }
        }
    }
}

if (!function_exists('unregister_globals')) {
    function unregister_globals()
    {
        if (ini_get(register_globals)) {
            $array = array('_REQUEST', '_SESSION', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }
}

if (!function_exists('session_register')) {
    function session_register()
    {
        $register_vars = func_get_args();
        foreach ($register_vars as $var_name)
        {
            $_SESSION[$var_name] = $GLOBALS[$var_name];
            if (!ini_get('register_globals'))
            {   $GLOBALS[$var_name] = &$_SESSION[$var_name]; }
        }
    }

    function session_is_registered($var_name)
    {   return isset($_SESSION[$var_name]); }

    function session_unregister($var_name)
    {   unset($_SESSION[$var_name]); }
}