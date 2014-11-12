<?php
/*
Copyright 2014 Peter Pitchford

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

if ((function_exists("session_status") && session_status() == PHP_SESSION_NONE) || !session_id()) {
    session_start();
}
if (file_exists("config.php")) {
    include "config.php";
} else {
    // user added site specific variables
    define("GEO_DEBUG_EMAIL", "");
    define("GEO_DEFAULT_EMAIL", "");
    define("GEO_DEFAULT_FROM", "");
    define("GEO_IMAGE_PATH", "");
    define("GEO_DEBUG_PASSWORD", "password");
}

spl_autoload_register('geolib_autoloader');

GeoDebug::init();
GeoHtml::setDoctype();



if (!defined("GEO_BASE")) {
    define("GEO_BASE", "");
}
function geolib_autoloader($class)
{
    
    $file=dirname(__FILE__)."/classes/" . $class . '.php';

    if (file_exists($file)) {
        include $file;
    }
}

function geoTag($tag, $text, $class = null, $id = null, $style = null, $atts = null)
{
    $GeoTag = new GeoTag($tag, $text, $class, $id, $style, $atts);
    return $GeoTag->baseTag();
}

function geoHtml($body = null, $head = null, $tidyTag = null, $start = null, $doctype = null)
{
    $page = new GeoHtml($body, $head, $start, $doctype);
    return $page->tag($tidyTag);
}

function geoHead(
    $title = null,
    $stylesheets = null,
    $scripts = null,
    $styles = null,
    $metas = null,
    $addlTags = null,
    $media = 'screen',
    $addHtml = null
) {
    return new GeoHead($title, $stylesheets, $scripts, $styles, $metas, $addlTags, $media, $addHtml);
}

//creates body
function geoBody($page, $onload = null, $class = null, $style = null, $id = null)
{
    return new GeoBody($page, $onload, $class, $style, $id);
}

function geoScript($src = null, $text = null, $type = "text/javascript")
{
    if (is_array($text)) {
        foreach ($text as $onetext) {
            $script .= geoScript($src, $onetext);
        }
        
        return $script;
    }
    
    $atts['type'] = $type;
    
    if ($src === true) {
        $text = "jQuery(function($) {" . $text . "});";
    } else {
        $atts['src'] = $src;
    }
    
    return geoTag("script", $text, null, null, null, $atts);
}

function geoNoScript($content)
{
    return geoTag('noscript', $content);
}

function geoLink($link = null, $text = null, $class = null, $target = null, $title = null, $id = null, $atts = null)
{
    $link = new GeoLink($link, $text, $class, $target, $title, $id, $atts);
    return $link->tag();
}

function geoAnchor($name)
{
    $link = new GeoLink();
    $link->setAtt("name",$name);
    $link->setAtt("id",$name);
    return $link->tag();
}

function geoJSLink($text = null, $id = null, $atts = null, $class = null, $title = null, $target = null)
{
    if ($atts && !is_array($atts)) {
        $newatts['onclick'] = $atts;
        $atts               = $newatts;
    }
    if ($class) {
        $class .= " geoJsLink";
    } else {
        $class = "geoJsLink";
    }
    $link = new GeoLink(null, $text, $class, $title, $target, $id, $atts);
    return $link->tag();
}

function geoAbsLink($link = null, $text = null, $class = null, $title = null, $target = null, $id = null, $atts = null)
{
    $link = new GeoLink("http://" . $_SERVER["HTTP_HOST"] . $link, $text, $class, $target, $title, $id, $atts);
    return $link->tag();
}

function geoSelect(
    $name = "selectname",
    $options = null,
    $selected = null,
    $id = null,
    $atts = null,
    $size = null,
    $class = null,
    $style = null
) {
    if ($options) {
        $select = new GeoSelect($name, $options, $selected, $id, $atts, $size, $class, $style);
        return $select->tag();
    }
}

function span($text, $class = null, $id = null, $style = null, $atts = null)
{
    $tagobj = new GeoTag('span', $text, $class, $id, $style, $atts);
    return $tagobj->baseTag();
}

function div($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('div', $text, $class, $id, $style, $atts);
}

function p($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('p', $text, $class, $id, $style, $atts);
}

function b($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('b', $text, $class, $id, $style, $atts);
}

function i($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('i', $text, $class, $id, $style, $atts);
}

function h1($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('h1', $text, $class, $id, $style, $atts);
}

function h2($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('h2', $text, $class, $id, $style, $atts);
}

function h3($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('h3', $text, $class, $id, $style, $atts);
}

function h4($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('h4', $text, $class, $id, $style, $atts);
}
function geoPre($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('pre', $text, $class, $id, $style, $atts);
}

function geoFieldSet(
    $html,
    $legend = null,
    $class = null,
    $legendClass = null,
    $id = null,
    $legendId = null,
    $style = null,
    $legendStyle = null
) {
    return geoTag("fieldset", geoLegend($legend, $legendClass, $legendId, $legendStyle) . $html, $class, $id, $style);
}

function geoLegend($html, $class, $id, $style)
{
    return geoTag("legend", $html, $class, $id, $style);
}

//creates source code tabs
function geoTabs($tabs = 1, $text = "   ")
{
    if ($text=== true) {
        $text="&nbsp;&nbsp;&nbsp;";
    }
    
    $tab='';
   
    for ($i=0; $i<$tabs; $i++) {
        $tab.=$text;
    }
   
    return $tab;
}

function geoImg(
    $src,
    $alt = '',
    $href = null,
    $title = null,
    $target = null,
    $host = null,
    $id = null,
    $class = null,
    $style = null,
    $atts = null,
    $rel = null
) {
    $imgobj = new GeoImg($src, $alt, $href, $title, $target, $host, $id, $class, $style, $atts, $rel);
    return $imgobj->tag();
}

function geoUpload($fileName = "filename", $id = null)
{
    return geoInput("file", $fileName, null, null, $id);
}

function geoForm(
    $inputs,
    $id = null,
    $action = null,
    $onsubmit = null,
    $class = null,
    $style = null,
    $enctype = false,
    $target = null,
    $method = 'post',
    $atts = null
) {
    if ($action===true) {
        $url    = parse_url($_SERVER["REQUEST_URI"]);
        $action = $url["path"];
    } elseif (!$action) {
        $action=$_SERVER["REQUEST_URI"];
    }

   
    if ($enctype) {
        $atts["enctype"] = "multipart/form-data";
    }
    
    $atts["action"] = $action;
    $atts["method"] = $method;
    
    if ($target) {
        $atts["target"] = $target;
    }
    
    // onsubmit is default, everything else requires an array
    if (is_array($onsubmit)) {
        foreach ($onsubmit as $name => $value) {
            $atts[$name] = $value;
        }
    } else {
        $atts["onsubmit"] = $onsubmit;
    }
    
    if ($action == null) {
        $action = $_SERVER["PHP_SELF"];
    }
    
    return geoTag('form', div($inputs, 'geoForm'), $class, $id, $style, $atts);
}

function geoTextArea(
    $name = null,
    $value = null,
    $rows = 5,
    $cols = 25,
    $atts = null,
    $id = null,
    $class = null,
    $style = null
) {
    
    if (!$id) {
        $id = $name;
    }
    
    $atts['name'] = $name;
    $atts['rows'] = $rows;
    $atts['cols'] = $cols;
    return geoTag("textarea", $value, $class, $id, $style, $atts);
}

function geoText(
    $name = 'textinput',
    $value = null,
    $id = null,
    $class = null,
    $atts = null,
    $size = null,
    $readonly = null,
    $style = null
) {
    if ($atts && !is_array($atts)) {
        $newatts['maxlength'] = $atts;
        $atts = $newatts;
    }
    $atts['size'] = $size;
    
    if ($readonly) {
        $atts['readonly'] = 'readonly';
    }
    
    return geoInput('text', $name, $value, $atts, $id, $class, $style);
}

function geoHidden($name, $value = null, $id = null, $atts = null, $class = null)
{
    $result = '';
    if (is_array($name)) {
        foreach ($name as $one) {
            $result .= geoHidden(
                Geo::Val($one[0]),
                Geo::Val($one[1]),
                Geo::Val($one[2]),
                Geo::Val($one[3]),
                Geo::Val($one[4])
            );
        }
        return $result;
    }
    if (!$id) {
        $id = true;
    } elseif ($id === true) {
        $id = null;
    }
    
    return geoInput('hidden', $name, $value, $atts, $id, $class);
}

function geoPassword($name, $value = null, $id = null, $atts = null, $class = null)
{
    return geoInput('password', $name, $value, $atts, $id, $class);
}

/**
 * Create an HTML submit button.
 * 
 * @param string       $type  Type if input element
 * @param string       $name  Name of returned value
 * @param string       $value Return value
 * @param string|array $atts  Any other atts. Can be string with "=" to indicate 1 attribute
 * @param string       $id    Id attribute 
 * @param string       $class Class attribute
 * @param string       $style Style attribute
 *
 * @return string HTML Submit Button 
 */
function geoInput($type = 'text', $name = null, $value = null, $atts = null, $id = null, $class = null, $style = null)
{
    if ($atts && !is_array($atts)) {
        $attsarr           = explode("=", $atts);
        $atts              = array();
        $atts[$attsarr[0]] = $attsarr[1];
    }
    
    if (!$id && strpos($name, "[") === false) {
        $id = $name;
    } elseif ($id === true) {
        $id = null;
    }
    
    $atts['name']  = $name;
    $atts['type']  = $type;
    $atts['value'] = $value;
    return geoTag("input", $value, $class, $id, $style, $atts);
}

/**
 * Create an HTML submit button.
 *
 * @param string $name        Name of returned value
 * @param string $text        Checkbox text
 * @param bool   $isChecked   Indicates that this box is checked
 * @param string $id          Id attribute. To add labels to the text, you must add $id
 * @param string $trueOrFalse Indicates type of return value 
 *                               Use 't' or 'f', in which cases checkbox value will return 't' or 'f' 
 *                                   instead of true or null
 *                               If 'f' is used, checkbox will reverse true for false.
 *                               When using checkbox arrays (as in checkboxName[]), use $trueOrFalse for the value
 * @param string $extLabel    External label. To create an unattached label, pass a true variable as $extlabel 
 *                                   and then use $extLabel as the label 
 * @param string $class       Class attribute
 *
 * @return string HTML Submit Button 
 */
function geoCheckbox(
    $name,
    $text = null,
    $isChecked = null,
    $id = null,
    $trueOrFalse = null,
    &$extLabel = null,
    $class = null
) {
    
    $atts = array();
    
    if ($trueOrFalse === true) {
        // use t/f
        $value = "t";
        $false = geoHidden($name, 'f');
        
        if ($isChecked && $isChecked != 'f') {
            // is checked is true
            $isChecked = true; // make it true
        } else {
            $isChecked = '';
        }
        
    } elseif ($trueOrFalse === false) {
        // use reverse t/f
        $value = "f";
        $false = geoHidden($name, 't');
        if ($isChecked && $isChecked != 'f') {
            // is checked is true
            $isChecked = ''; // make it false
        } else {
            $isChecked = true;
        }
        
    } else {
        $false = '';
        
        if ($trueOrFalse) {
            $value = $trueOrFalse;
        } else {
            $value = true;
        }
    }
    if ($isChecked) {
        $atts['checked'] = 'checked';
    }
    
    if (!$id) {
        $id = $name;
    }
    
    $result = geoInput('checkbox', $name, $value, $atts, $id, $class);
   
    if ($text) {
        $label = geolabel(" " . $text, $id, $class);
        if ($extLabel) {
            $extLabel = $label;
        } else {
            $result .= " " . $label;
        }
    } else {
        $extLabel = '';
    }
    
    return $false . $result;
}

/**
 * Create an HTML submit button.
 *
 * @param string       $value Submit button text
 * @param string       $name  Name of returned value 
 * @param string       $class Class attribute
 * @param string       $id    Id attribute
 * @param string|array $atts  Any other atts. If string, will be used as "onclick" attribute 
 * @param string       $style Style attribute
 *
 * @return string HTML Submit Button 
 */
function geoSubmit($value = "Submit", $name = null, $class = null, $id = null, $atts = null, $style = null)
{
    if ($atts && !is_array($atts)) {
        $atts2["onclick"] = $atts;
        $atts             = $atts2;
    }
    
    // id behaves the opposite of geoInput();
    if (!$id) {
        $id = true;
    }elseif($id===true){
        $id=null;    
    }
    
    
    return geoInput('submit', $name, $value, $atts, $id, $class, $style);
}

/**
 * Create an HTML image submit button.
 *
 * @param string       $src   Image path
 * @param string       $name  Name of return value
 * @param string       $atts  Any other atts. If string, will be used as "onclick" attribute 
 * @param array|string $id    Id attribute
 * @param string       $class Class attribute
 * @param string       $style Style attribute
 *
 * @return string HTML image submit button 
 */
function geoImgSubmit($src, $name = null, $atts = null, $id = null, $class = null, $style = null)
{
    if ($atts && !is_array($atts)) {
        $atts["onclick"] = $atts;
    }
    
    $atts['src'] = $src;
    return geoInput('image', $name, null, $atts, $id, $class, $style);
}

/**
 * Create HTML button.
 *
 * @param string       $value Return value
 * @param string       $id    Id Attribute 
 * @param string       $class Class Attribute
 * @param array|string $atts  Any other atts. If string, will be used as "onclick" attribute 
 * @param string       $name  Name of returned value
 * @param string       $style Style attribute
 *
 * @return string HTML list item 
 */
function geoButton($value, $id = null, $class = null, $atts = null, $name = null, $style = null)
{
    if ($atts && !is_array($atts)) {
        $newatts['onclick'] = $atts;
        $atts               = $newatts;
    }
    return geoInput('button', $name, $value, $atts, $id, $class, $style);
}

/**
 * Create a radio button.
 *
 * @param string $name      Name of radio button group
 * @param string $id        Id attribute 
 * @param string $label     Text of label 
 * @param bool   $isChecked Indicates that this element is selected  
 * @param string $value     Radio group will return this value if this element is selected
 * @param array  $atts      Any other attributes 
 * @param string $class     Class attribute 
 * @param bool   $isScale   Indicates that the radio button and its label are returned in 2 divs, 
 *                              instead of together in one line. This is handy for creating 1-10 scales 
 *
 * @return string HTML list item 
 */
function geoRadio(
    $name,
    $id,
    $label = null,
    $isChecked = null,
    $value = null,
    $atts = null,
    $class = null,
    $isScale = null
) {

    if (!isset($value)) {
        $value = $id;
    }
    
    if ($isChecked || (isset($_POST[$name]) && $_POST[$name] == $value) || ($value === "default" && !$_POST[$name])) {
        $atts['checked'] = 'checked';
    }
    
    $radio[] = geoInput('radio', $name, $value, $atts, $id, $class);
    
    if ($label) {
        $radio[] = geoLabel($label, $id);
    }
    
    if ($isScale) {
        return div($radio);
    }
    
    return implode("&nbsp;", $radio);
    
}


/**
 * Create a group of radio button as an HTML string or list, or as an array
 *     All radios in 1 group have the same name and different ids
 *
 * @param string $name        Name of Radio group. Also becomes id, with added index. 
 * @param array  $titles      An array of $values=>$titles
 * @param string $default     Value of selected radio button
 * @param mixed  $listClass   If this value is supplied:
 *                                If true, function returns an array of radio buttons. 
 *                                If string, function returns a list with this class.
 * @param string $break       Value of HTML between buttons
 *                                If true, function returns an array of radio buttons.                         
 * @param string $origDefault Original default value (This item receives the "default" class)
 * @param string $inputClass  Each radio input is given this class 
 * @param bool   $isScale     Causes radiobuttons to be returned inside with "geoScale" class 
 *
 * @return array, list or string of rsdio buttons
 */
function geoRadios(
    $name,
    $titles,
    $default = null,
    $listClass = null,
    $break = null,
    $origDefault = null,
    $inputClass = null,
    $isScale = null
) {

    if ($titles) {
        foreach ($titles as $k => $title) {
            $inputClasses = array();
            
            if ($origDefault == $k) {
                $inputClasses[] = "default";
            }
            
            if ($inputClass) {
                $inputClasses[] = $inputClass;
            }
            
            $radios["li_".$name . "_" . $k]=geoRadio(
                $name,
                $name . "_" . $k,
                $title,
                geoIf($default == $k),
                $k,
                null,
                geoIf($inputClasses, implode(" ", $inputClasses)),
                $isScale
            );
        }
    }
    if ($isScale) {
        return div($radios, 'geoScale') . div(null, null, null, 'clear:both');
    }
    
    if ($listClass === true || $break === true) {
        // no list, just return array
        return $radios;
    }
    
    if ($listClass) {
        return geoList($radios, $listClass);
    }
    
    // return $break between each radio button
    return implode($break, $radios);
}


/**
 * Create HTML label.
 *
 * @param string $text  Content of list item array to test
 * @param string $for   For of label 
 * @param string $class Id of label 
 * @param string $id    Style of label 
 *
 * @return string HTML list item 
 */
function geolabel($text, $for, $class = null, $id = null)
{
    return geoTag("label", $text, $class, $id, null, array("for" => $for));
}


/**
 * Create HTML list item. (Usually not neccesary since geoList takes arrays of content values)
 *
 * @param string $text  Content of list item 
 * @param string $class Class of list item 
 * @param string $id    Id of list item 
 * @param string $style Style of list item 
 *
 * @return string HTML list item 
 */
function geoItem($text = null, $class = null, $id = null, $style = null)
{
    $item = new GeoTag('li', $text, $class, $id, $style);
    return $item;
}


/**
 * Create HTML list or array of lists
 *
 * @param array   $lists       1 or 2 dimensional array
 *       A 2 dimensional array will result in a list for each array. 
 *       To have ids for each list item, 
 *           make item keys non integer, such as "item_3"
 * @param string  $class       List Class or classes
 *   If lists is multidemensional: 
 *       If class is a string, it becomes the class for all lists.
 *       If class is an array, each row is a class for 1 list
 *   If lists is not multidementional
 *      If class is a string, class is a list class.
 *      If class is an array, each row becomes a class for 1 list item
 * @param string  $id          List id
 * @param integer $cols        Number of columns. Break $lists into one list for each column
 * @param string  $style       List Style
 * @param string  $lt          List type (either ul or ol) Default is ul 
 * @param array   $itemStyles  Array of styles for each list item
 * @param array   $itemClasses Array of classes for each list item
 * @param array   $listItemIds Array of ids for each list item 
 *
 * @return string End tag
 */
function geoList(
    $lists = null,
    $class = null,
    $id = null,
    $cols = 1,
    $style = null,
    $lt = 'ul',
    $itemStyles = null,
    $itemClasses = null,
    $listItemIds = null
) {
    
    
    
    if (!geoIsMultiArr($lists) && is_array($class)) {
        $itemClasses = $class;
        unset($class);
    }
    $lists    = geoMultiArr($lists);
    $allLists = '';
    foreach ($lists as $key => $list) {
        $list = new GeoList(
            $list,
            Geo::ifArr($class, $key),
            $id,
            Geo::ifArr($cols, $key),
            Geo::ifArr($style, $key),
            Geo::ifArr($lt, $key)
        );
        $allLists .= $list->tag($itemClasses, $listItemIds, $itemStyles);
    }
    return $allLists;
}


/**
 * Test for Multidimensional array
 *
 * @param array $array array to test
 *
 * @return bool indicates a multidimensional array
 */
function geoIsMultiArr($array)
{
    return count($array) != count($array, 1);
}


/**
 * Checks value, create a 2 dimensional array if it isn't already a 2 dimensional array
 *
 * @param array  $array Either a 1 or 2 dimensional array
 * @param string $name  Key value for new 2 dimensional array
 *
 * @return array 2 dimensional array
 */
function geoMultiArr($array, $name = "0")
{
    if (geoIsMultiArr($array)) {
        return $array;
    }
    
    return array(
        $name => $array
    );
}
/**
 * Create an HTML table
 *
 * @param array  $rows        Text
 * @param string $class       Table class
 * @param array  $titles      Optional first row as separate content array
 * @param string $titleClass  Optional class of first row
 * @param array  $rowClasses  Classes for rows (each key must match $rows keys) 
 * @param array  $colClasses  Classes for columns
 * @param string $id          Table id
 * @param int    $cellspacing Cellspacing for all table cells
 * @param int    $cellpadding Cellpadding for all table cells
 *
 * @return object GeoCell Object
*/
function geoTable(
    $rows,
    $class = null,
    $titles = null,
    $titleClass = null,
    $rowClasses = null,
    $colClasses = null,
    $id = null,
    $cellspacing = 0,
    $cellpadding = null
) {
    
    $tableobj = new GeoTable(
        $rows,
        $class,
        $rowClasses,
        $colClasses,
        $id,
        $cellspacing,
        $cellpadding
    );
    
    if ($titles) {
        if (!is_array($titles)) {
            $titles = explode(", ", $titles);
        }
        
        $tableobj->setTitles($titles);
        
        if ($titleClass) {
            $tableobj->setRowClass(0, $titleClass);
        }
        
    }
    if (!$class) {
        $tableobj->setAtt("border", 1);
        $tableobj->setAtt("cellpadding");
    }
    if ($colClasses) {
        $colClasses = Geo::arr($colClasses);
        
        foreach ($colClasses as $i => $colClass) {
            $tableobj->setColClass($i, $colClass);
        }
    }
    
    return $tableobj->tag();
}

// tags without end tags.
/**
 * Create start tags
 *
 * @param string $tag   Name of HTML tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML Tag without the end tag
 */
function geoStart($tag, $class = null, $id = null, $style = null, $atts = null)
{
    $GeoTag = new GeoTag($tag, null, $class, $id, $style, $atts);
    return $GeoTag->baseTag(true);
}

/**
 * Create end tags
 *
 * @param array $tags tags to end
 *
 * @return string End tag
 */
function geoEnd($tags = null)
{
    $geoEnd = '';
    if (!$tags) {
        $tags = array(
            'body',
            'html'
        );
    }
    
    $tags = Geo::arr($tags);
    
    foreach ($tags as $tag) {
        $GeoTag = new GeoTag($tag);
        if ($tag == "body") {
            $geoEnd .= GeoDebug::vars();
        }
        
        $geoEnd .= $GeoTag->baseTag(null, true);
    }
    return $geoEnd;
}

/**
     * Formats debugging variables for display, prints result instead of returning result
     *
     * @param mixed  $variable Any type of variable being debugged
     * @param string $name     Name used for displaying debug variable
     * @param bool   $isHtml   Display strings as HTML. Default displays hightlighted HTML tags.
     *
     * @return void
     */
function geovar($variable, $name = null, $isHtml = null)
{
    echo GeoDebug::vr($variable, $name, $isHtml);
    echo GeoDebug::trace($name, null, true);
}

/**
 * Allows inline test for value.
 * Can test constants but not undefined variables.
 *
 * @param mixed $var     Variable to be tested for value
 * @param mixed $result1 Value to be returned if test is passed
 * @param mixed $result2 Value to be returns if test fails
 *
 * @return mixed Result of test
*/
function geoif($var, $result1 = true, $result2 = null)
{
    if ($var) {
        return $result1;
    }
    
    return $result2;
}

// Wrappers

/**
 * Create a table cell for a geoWTable object. Allows use of function syntax instead of object-> syntax
 *
 * @param string $celltext Text
 * @param string $colspan  Column span
 * @param string $rowspan  Row span
 * @param string $class    Class
 * @param string $valign   Valign
 * @param string $style    Style
 * @param string $id       Id
 *
 * @return object GeoCell Object
*/
function geoCell($celltext, $colspan = null, $rowspan = null, $class = null, $valign = null, $style = null, $id = null)
{
    return new GeoCell($celltext, $colspan, $rowspan, $class, $valign, $style, $id);
}

/**
 * Adds content to debugging array
 *
 * @param mixed  $variable     Variable being debugged
 * @param string $name         Name used for displaying debug variable
 * @param bool   $addBacktrace Determines whether to add backtrace to debug display
 * @param bool   $return       Determines whether debugging content is displayed
 *                                 or saved. Default is saved.
 * @param bool   $noHighlight  Display strings as HTML. Default displays HTML tags.
 * @param bool   $always       Always debug, even when not in debugging mode
 *
 * @return string debug content is formatted with HTML
 */
function geoDb(
    $variable = null,
    $name = "debug",
    $addBacktrace = null,
    $return = null,
    $noHighlight = null,
    $always = null
) {
    GeoDebug::db($variable, $name, $addBacktrace, $return, $noHighlight, $always, 1);
}

/**
 * Creates validated text input fields, or simply displays values
 *
 * @param string $name           Input name
 * @param array  $values         Reference to array of values (usually from database)
 * @param array  $missing        Reference to array of validation messages 
 * @param string $prefix         Prefix to add to name
 * @param string $printableClass Display data as strings instead of text input fields, and use this as class
 *
 * @return HTML text input with validation
 */
function geoValidText($name, &$values, &$missing, $prefix = null, $printableClass = null)
{
    if (isset($values[$name])) {
        $value=$values[$name];
    } else {
        $value='';
    }
    
    //geoDb($value,'thevalue');
    if ($printableClass) {
        return span($value, $printableClass);
    }
    
    if ($prefix) {
        $prefixName=$prefix."_".$name;
    } else {
        $prefixName=$name;
    }
    
    
    $input=geoInput('text', $prefixName, $value, null, $prefixName);
    if (isset($missing[$prefixName])) {
        $input.=div($missing[$prefixName], 'errorText');
    }
    return $input;
}
