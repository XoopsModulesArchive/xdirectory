<?php

/**
 *  mylinks Utility Class Elements
 *
 * @copyright::  ZySpec Incorporated
 * @license::    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package::    mylinks
 * @subpackage:: class
 * @author::     zyspec (owners@zyspec.com)
 * @version::    $Id$
 * @since::     File available since Release 3.11
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access') ;

/**
 * MylinksUtility
 *
 * @package::   mylinks
 * @author::    zyspec (owners@zyspec.com), Herve Thouzard
 * @copyright:: Copyright (c) 2010 ZySpec Incorporated, Herve Thouzard
 * @version::   $Id$
 * @access::    public
 */
class MylinksUtility
{
/**
     * Sanitize input variables
     * @param string $global the input array ($_REQUEST, $_GET, $_POST)
     * @param unknown_type $key the array key for variable to clean
     * @param unknown_type $default the default value to use if filter fails
     * @param string $type the variable type (string, email, url, int)
     * @param array $limit 'min' 'max' keys - the lower/upper limit for integer values
     * @return number|Ambigous <boolean, unknown>
     */
    function mylinks_cleanVars( &$global, $key, $default = '', $type = 'int', $limit=NULL) {
        switch ( $type )
        {
            case 'string':
                $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_MAGIC_QUOTES ) : $default;
                break;
            case 'email':
                $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_EMAIL ) : $default;
                break;
            case 'url':
                $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_URL ) : $default;
                break;
            case 'int':
            default:
                $default = intval($default);
                $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_NUMBER_INT ) : false;
                if ( isset($limit) && is_array($limit) && ( false !== $ret) ) {
                    if (array_key_exists('min', $limit)){
                        $ret = ( $ret >= $limit['min'] ) ? $ret : false ;
                    }
                    if (array_key_exists('max', $limit)){
                        $ret = ( $ret <= $limit['max'] ) ? $ret : false ;
                    }
                }
                break;
        }
        $ret = ($ret === false) ? $default : $ret ;
        return $ret;
    }
}
?>
