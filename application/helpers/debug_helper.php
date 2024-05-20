<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * firephp
 *
 * @param    $var array, string, integer
 * @type    string : "log", "warn", "error"
 */
function firephp($value, $type = 'log')
{
    if( $type != 'log' AND $type != 'warn' AND $type != 'error')
    {
        $type = 'warn';
    }
    $CI =& get_instance();
    $CI->firephp->{$type}($value);
}

//------------------------------------------------------------------------------

/**
 * firephp
 *
 * @type    string : log, warn, error
 */
function firephp_last_query($type = 'log')
{
    if( $type != 'log' AND $type != 'warn' AND $type != 'error')
    {
        $type = 'warn';
    }
    $CI =& get_instance();
    $CI->firephp->{$type}($CI->db->last_query());
}

//------------------------------------------------------------------------------

/**
 * Outputs the query result
 *
 * @type    string : log, warn, error
 */
function firephp_session($type = 'log')
{
    if( $type != 'log' AND $type != 'warn' AND $type != 'error')
    {
        $type = 'warn';
    }
    $CI =& get_instance();
    $CI->firephp->{$type}($CI->session->all_userdata());
}

//------------------------------------------------------------------------------
/**
 * Outputs an array or variable
 *
 * @param    $var array, string, integer
 * @return    string
 */
function debug_var($var)
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'qqbro') <= 0) {
        return false;
    }

    if (!is_cli()) {
        echo _before();
    }
    if (func_num_args() > 1) {
        $var = func_get_args();
    }
    if (is_array($var) || is_object($var))
    {
        print_r($var);
    }
    else
    {
        echo $var;
    }
    if (!is_cli()) {
        echo _after();
    }
}
function dd($var)
{
    if (func_num_args() > 1) {
        $var = func_get_args();
    }
    debug_var($var);
}
function dq()
{
    return debug_last_query();
}


function debug_stacktrace()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'qqbro') <= 0) {
        return false;
    }

    if (!is_cli()) {
        echo _before();
    }

    debug_print_backtrace();

    if (!is_cli()) {
        echo _after();
    }
}

//------------------------------------------------------------------------------

/**
 * Outputs the last query
 *
 * @return    string
 */
function debug_last_query()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'qqbro') <= 0) {
        return false;
    }

    $CI =& get_instance();
    echo _before();
    echo $CI->db->last_query();
    echo _after();
}

//------------------------------------------------------------------------------

/**
 * Outputs the query result
 *
 * @param    $query object
 * @return    string
 */
function debug_query_result($query = null)
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'qqbro') <= 0) {
        return false;
    }

    echo _before();
    print_r($query->result_array());
    echo _after();
}

//------------------------------------------------------------------------------

/**
 * Outputs all session data
 *
 * @return    string
 */
function debug_session()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'qqbro') <= 0) {
        return false;
    }

    $CI =& get_instance();
    echo _before();
    print_r($CI->session->all_userdata());
    echo _after();
}

//------------------------------------------------------------------------------

/**
 * Logs a message or var
 *
 * @param    $message array, string, integer
 * @return    string
 */
function debug_log($message = '')
{
    is_array($message) || is_object($message) ? log_message('debug', print_r($message)) : log_message('debug', $message);
}

//------------------------------------------------------------------------------

/**
 * _before
 *
 * @return    string
 */
function _before()
{
    $before = '<div style="padding:10px 20px 10px 20px; background-color:#fbe6f2; border:1px solid #d893a1; color: #000; font-size: 12px;>'."\n";
    $before .= '<h5 style="font-family:verdana,sans-serif; font-weight:bold; font-size:18px;">Debug Helper Output</h5>'."\n";
    $before .= '<pre>'."\n";
    return $before;
}

//------------------------------------------------------------------------------

/**
 * _after
 *
 * @return    string
 */

function _after()
{
    $after = '</pre>'."\n";
    $after .= '</div>'."\n";
    return $after;
}