<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */

/*function smarty_modifier_truncate($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}*/

/*function smarty_modifier_truncate($string, $length = 80, $etc = '...', $code = 'UTF-8')
{
if ($length == 0)
return '';
if ($code == 'UTF-8') {
$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
}
else {
$pa = "/[\x01-\x7f]|[\xa1-\xff][\xa1-\xff]/";
}
preg_match_all($pa, $string, $t_string);
if (count($t_string[0]) > $length){
return join('', array_slice($t_string[0], 0, $length)) . $etc;
}
return join('', array_slice($t_string[0], 0, $length));
}*/


function smarty_modifier_truncate($_String, $_Length, $etc='...',$_Start = 0, $_Encode = 'UTF-8') {
$v = 0;
if ($_Encode == 'UTF-8') {
$_Pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
preg_match_all($_Pa, $_String, $_Rarray);
$_SLength = count($_Rarray[0]);

        if ($_SLength >= $_Length){
for ($i = $_Start,$v=0; $i < $_SLength; $i++) {
if ($v >= $_Length * 2 - 1){
return $_RS . $etc;
}
$num=ord($_Rarray[0][$i]);
if ($num>129){
$v += 2;
}else{
$v++; 
}
$_RS .= $_Rarray[0][$i];
} 
}
} else {
$_Start = $_Start * 2;
$_Length = $_Length * 2;
$_Len    = strlen($_String);

        if ($_Len >= $_Length){
for ($i = $_Start; $i < $_Len; $i++) {
if ($v >= $_Length - 1){
return $_Rstring . $etc;
}
if (ord(substr($_String, $i, 1)) > 129) {
$_Rstring .= substr($_String, $i, 2);
$v += 2;
$i++;
} else {
$_Rstring .= substr($_String, $i, 1);
$v++;
}
}
}
}
return $_String;
}

/* vim: set expandtab: */

?>
