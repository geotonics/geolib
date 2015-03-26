<?php
/**
* Document for geolib functions and class wrappers
 *
 * PHP Version 5.6
 *
 * @category GeolibFile
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @link     http://geotonics.com/#geolib
*/

/**
 * Loads geolib PHP class files.
 *
 * @param string $class Name of class to load
 *
 * @return void
 */
function geolib_autoloader($class)
{
    $file=dirname(__FILE__)."/classes/" . $class . '.php';

    if (file_exists($file)) {
        include $file;
    }
}

/**
 * Create an HTML tag.
 *
 * @param string $tag   tag name
 * @param string $text  Content of tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML i tag
 */
function geoTag($tag, $text, $class = null, $id = null, $style = null, $atts = null)
{
    $GeoTag = new GeoTag($tag, $text, $class, $id, $style, $atts);
    return $GeoTag->baseTag();
}

/**
 * Create HTML tag
 *
 * @param string|object $body    HTML body tag or GeoBody object
 * @param string|object $head    A GeoHead object, usually from geoHead(). A string for the title can be used
 *                                   if no other head attributes are required.
 * @param bool          $tidyTag Determines whether to tidy up html with proper tabs
 * @param bool          $start   A flag to indicte that this tag should only return the start tag and the content.
 *                                   This allows direct output of any other content before the end tag.
 * @param string        $doctype Doctype for this document
 *
 * @return string HTML document
*/
function geoHtml($body = null, $head = null, $tidyTag = null, $start = null, $doctype = null)
{
    $page = new GeoHtml($body, $head, $start, $doctype);
    return $page->tag($tidyTag);
}

/**
 * Create an html head tag
 *
 * @param string       $title       Document title
 * @param string|array $stylesheets Paths to document stylesheets. Can be 1 path or an array
 * @param string|array $scripts     Paths to document scripts. Can be either 1 path or an array
 * @param string       $styles      Styles for style tag in head
 * @param array        $metas       Content of metatags
 * @param string|array $addlTags    Any additional tags for head
 *                                      addlTags can be multi or non multi. Examples:
 *                                      array:$tags["script"][$script]=array("type"=>'text/script');
 *                                      $addlTags['link'][]=array("rel"=>'goback');
 *                                      $addlTags['link']=array("rel"=>'goback');
 * @param string       $media       Media attribute
 * @param string       $addlHtml    Any additional html for head
 *
 * @return string HTML head tag
*/
function geoHead(
    $title = null,
    $stylesheets = null,
    $scripts = null,
    $styles = null,
    $metas = null,
    $addlTags = null,
    $media = 'screen',
    $addlHtml = null
) {
    return new GeoHead($title, $stylesheets, $scripts, $styles, $metas, $addlTags, $media, $addlHtml);
}
/**
 * Create HTML body
 *
 * @param string $page   Content of body tag
 * @param string $onload Javascript to be run on page load
 * @param string $class  Class attribute
 * @param string $style  Style attribute
 * @param string $id     Id attribute
 *
 * @return string HTML body
*/
function geoBody($page, $onload = null, $class = null, $style = null, $id = null)
{
    return new GeoBody($page, $onload, $class, $style, $id);
}

/**
 * Create an link for javascript actions.
 *
 * @param string|array $src  Path to javascript file
 * @param string       $text Javascript string
 * @param string       $type Type of script
 *
 * @return string HTML a tag
*/
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

/**
 * Create noscript tag
 *
 * @param string $content Non javascript content
 *
 * @return noscript tag
 */
function geoNoScript($content)
{
    return geoTag('noscript', $content);
}

/**
 * Create an http link.
 *
 * @param string $link   Link destination (with or without host)
 * @param string $text   Text of link
 * @param string $title  Title attribute
 * @param string $target Target attribute
 * @param string $class  Class attribute
 * @param string $id     Id attribute
 * @param array  $atts   Any other attributes
 *
 * @return string HTML a tag
*/
function geoLink($link = null, $text = null, $title = null, $target = null, $class = null, $id = null, $atts = null)
{
    $link = new GeoLink($link, $text, $title, $target, $class, $id, $atts);
    return $link->tag();
}


/**
 * Create HTML anchor
 *
 * @param string $name Anchor name (also given to id)
 *
 * @return string HTML anchor
 */
function geoAnchor($name)
{
    $link = new GeoLink();
    $link->setAtt("name", $name);
    $link->setAtt("id", $name);
    return $link->tag();
}

/**
 * Create an link for javascript actions.
 *
 * @param string $text   Text of link
 * @param string $id     Id attribute
 * @param string $title  Title attribute
 * @param string $class  Class attribute
 * @param array  $atts   Any other attributes
 * @param string $target Target attribute
 *
 * @return string HTML a tag
*/
function geoJSLink($text = null, $id = null, $title = null, $class = null, $atts = null, $target = null)
{
    if ($atts && !is_array($atts)) {
        $newatts['onclick'] = $atts;
        $atts = $newatts;
    }
    if ($class) {
        $class .= " geoJsLink";
    } else {
        $class = "geoJsLink";
    }
    
    $atts["class"]=$class;
    $class=null;
    $link = new GeoLink(null, $text, $title, $target, $class, $id, $atts);
    return $link->tag();
}

/**
 * Create an absolute http link to a location on this local server.
 *
 * @param string $link   Relative link destination (without host)
 * @param string $text   Text of link
 * @param string $class  Class attribute
 * @param string $title  Title attribute
 * @param string $target Target attribute
 * @param string $id     Id attribute
 * @param array  $atts   Any other attributes
 *
 * @return string HTML a tag
*/
function geoAbsLink($link = null, $text = null, $class = null, $title = null, $target = null, $id = null, $atts = null)
{
    $link = new GeoLink("http://" . $_SERVER["HTTP_HOST"] . $link, $text, $class, $target, $title, $id, $atts);
    return $link->tag();
}

/**
 * Create an HTML select tag
 *
 * @param string            $name     Name of select tag
 * @param array             $options  1 or 2 dimensional array of options
 *     If 1 each row is  $name=>$value
 *     If 2 each array is 0=>name 1=>value 2=>class
 * @param string            $selected name of selected option
 * @param string            $id       id of select tag
 * @param array|string|bool $atts     attributes of select tag
 *     If array, is atts of select tag.
 *     If string, is a single default att
 *     If PHP true, att is onchange=>javascript to submit form
 * @param string            $size     size att of select tag
 * @param string            $class    class of select tag
 * @param string            $style    style of select tag
 *
 * @return string HTML select tag
*/
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

/**
 * Create an span div tag.
 *
 * @param string $text  Content of span tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML span tag
 */
function span($text, $class = null, $id = null, $style = null, $atts = null)
{
    $tagobj = new GeoTag('span', $text, $class, $id, $style, $atts);
    return $tagobj->baseTag();
}

/**
 * Create an HTML div tag.
 *
 * @param string $text  Content of pre tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML div tag
 */
function div($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('div', $text, $class, $id, $style, $atts);
}

/**
 * Create an HTML p tag.
 *
 * @param string $text  Content of p tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML p tag
 */
function p($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('p', $text, $class, $id, $style, $atts);
}

/**
 * Create an HTML b tag.
 *
 * @param string $text  Content of b tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML b tag
 */
function b($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('b', $text, $class, $id, $style, $atts);
}


/**
 * Create an HTML i tag.
 *
 * @param string $text  Content of i tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML i tag
 */
function i($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('i', $text, $class, $id, $style, $atts);
}
/**
 * Create an HTML h1 tag.
 *
 * @param string $text  Content of h1 tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML h1 tag
 */
function h1($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('h1', $text, $class, $id, $style, $atts);
}

/**
 * Create an HTML h2 tag.
 *
 * @param string $text  Content of h2 tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML h2 tag
 */
function h2($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('h2', $text, $class, $id, $style, $atts);
}

/**
 * Create an HTML h3 tag.
 *
 * @param string $text  Content of h3 tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML h3 tag
 */
function h3($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('h3', $text, $class, $id, $style, $atts);
}

/**
 * Create an HTML h4 tag.
 *
 * @param string $text  Content of h4 tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML h4 tag
 */
function h4($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('h4', $text, $class, $id, $style, $atts);
}

/**
 * Create an HTML pre tag.
 *
 * @param string $text  Content of pre tag
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 * @param array  $atts  Any other attributes
 *
 * @return string HTML pre field
 */
function geoPre($text, $class = null, $id = null, $style = null, $atts = null)
{
    return geoTag('pre', $text, $class, $id, $style, $atts);
}

/**
 * Create an HTML fieldset tag.
 *
 * @param string $html        Content of fieldset tag
 * @param string $legend      HTML Content of legend tag
 * @param string $class       Class attribute
 * @param string $legendClass Legend class attribute
 * @param string $id          Id attribute
 * @param string $legendId    Legend id attriute
 * @param string $style       Style attribute
 * @param string $legendStyle Legend Style attribute
 *
 * @return string HTML password field
 */
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

/**
 * Create an HTML legend tag. This is usually not called directly.
 *    Use geoFieldSet to create a fieldset tag with a legend
 *
 * @param string $html  Tag content
 * @param string $class Class attribute
 * @param string $id    Id attribute
 * @param string $style Style attribute
 *
 * @return string HTML password field
 */
function geoLegend($html, $class, $id, $style)
{
    return geoTag("legend", $html, $class, $id, $style);
}


/**
 * Create tabs in source code (Can also be used to create any number of repetitions of any text)
 *
 * @param string $tabs Number of tabs
 * @param string $text Value to use as tab
 *
 * @return string Text of tabs
 */
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

/**
 * Create an HTML img field. (for images)
 *
 * @param string $src    Source (path) of image
 * @param string $alt    Alt attribut
 * @param string $href   Link destination, if link is desired.
 * @param string $title  Title attribute of link
 * @param string $target Target attribute of link
 * @param string $host   Host of image
 * @param string $id     Id attribute
 * @param string $class  Class attribute
 * @param string $style  Style attribute
 * @param array  $atts   Any other image atts.
 * @param string $rel    Image rel attribute of link
 *
 * @return string HTML img field
 */
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

/**
 * Create an HTML file upload field.
 *
 * @param string $fileName Name of file value which will contain the name of the selected file.
 * @param string $id       Id attribute
 *
 * @return string HTML file upload field
 */
function geoUpload($fileName = "filename", $id = null)
{
    return geoInput("file", $fileName, null, null, $id);
}

/**
 * Create an HTML form.
 *
 * @param string|array $inputs  Form content. Can be a string or an array of strings.
 *    Strings can be input fields or elements that contain input fields.
 * @param string       $id      Id attribute
 * @param string||true $action  Action attribute. Contains destination of form submit.
 *    If true, the action is the page that contains the form.
 * @param string|array $atts    Any other atts. If this is a string, it is assumed to be an onsubmit attribute
 *    The onsubmit attribute should contain javascript which will run when the form is submitted.
 * @param string       $class   Class attribute
 * @param string       $style   Style attribute
 * @param string       $enctype Enctype attribute
 * @param string       $target  Target attribute
 * @param boolean      $method  Method attribute
 *
 * @return string HTML form
 */
function geoForm(
    $inputs,
    $id = null,
    $action = null,
    $atts = null,
    $class = null,
    $style = null,
    $enctype = false,
    $target = null,
    $method = 'post'
) {
    if ($action===true) {
        $url    = parse_url($_SERVER["REQUEST_URI"]);
        $action = $url["path"];
    } elseif (!$action) {
        $action=$_SERVER["REQUEST_URI"];
    }

    // onsubmit is default, everything else requires an array
    if (is_array($atts)) {
        foreach ($atts as $name => $value) {
            $atts[$name] = $value;
        }
    } else {
        $atts["onsubmit"] = $atts;
    }
    if ($enctype) {
        $atts["enctype"] = "multipart/form-data";
    }
   
    $atts["action"] = $action;
    $atts["method"] = $method;
   
    if ($target) {
        $atts["target"] = $target;
    }
   
    if ($action == null) {
        $action = $_SERVER["PHP_SELF"];
    }
   
    return geoTag('form', div($inputs, 'geoForm'), $class, $id, $style, $atts);
}

/**
 * Create an HTML textarea field.
 *
 * @param string       $name  Name of value retured by this field
 * @param string       $value Value returned by this field
 * @param string       $class Class attribute
 * @param string       $id    Id attribute
 * @param string       $style Style attribute
 * @param string       $rows  Number of rows (can be overwritten with styles)
 * @param string       $cols  Number of Columns (can be overwritten with styles)
 * @param string|array $atts  Any other atts. Can be string with "=" to indicate 1 attribute
 *
 * @return string HTML textarea field
 */
function geoTextArea(
    $name = null,
    $value = null,
    $class = null,
    $id = null,
    $style = null,
    $rows = 10,
    $cols = 25,
    $atts = null
) {
   
    if (!$id) {
        $id = $name;
    }
   
    $atts['name'] = $name;
    $atts['rows'] = $rows;
    $atts['cols'] = $cols;
    return geoTag("textarea", $value, $class, $id, $style, $atts);
}

/**
 * Create an HTML text field.
 *
 * @param string       $name     Name of value retured by this field
 * @param string       $value    Value returned by this field
 * @param string       $id       Id attribute
 * @param string       $class    Class attribute
 * @param string|array $atts     Any other atts. Can be string with "=" to indicate 1 attribute
 * @param string       $size     Size attribute
 * @param boolean      $readonly Indicates that this field is readonly
 * @param string       $style    Style attribute
 *
 * @return string HTML text field
 */
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

/**
 * Create HTML hidden field or fields.
 *
 * @param string|array $name  Name of value retured by this field.
 *    If name is a 3 dimensional array, this function returns a hidden fields for each array in the array.
 *    Each value in the array must be an array of arguments in the same order as the funciton's parameters.
 * @param string       $value Value returned by this field
 * @param string       $id    Id attribute
 * @param string|array $atts  Any other atts. Can be string with "=" to indicate 1 attribute
 * @param string       $class Class attribute
 *
 * @return string HTML hidden field or fields
 */
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

/**
 * Create an HTML password field.
 *
 * @param string       $name  Name of value retured by this field
 * @param string       $value Value returned by this field
 * @param string       $id    Id attribute
 * @param string|array $atts  Any other atts. Can be string with "=" to indicate 1 attribute
 * @param string       $class Class attribute
 *
 * @return string HTML password field
 */
function geoPassword($name, $value = null, $id = null, $atts = null, $class = null)
{
    return geoInput('password', $name, $value, $atts, $id, $class);
}

/**
 * Create an HTML submit button.
 *
 * @param string       $type  Type if input element
 * @param string       $name  Name of value retured by this field
 * @param string       $value Value returned by this field
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
    
    /*
    // If value is true, use $_POST[$name]
    if($value===true){
        if(isset($_POST[$name])){
            $value=$_POST[$name];
        } else {
            $value=null;
        }
    }
    */
    
    $atts['name']  = $name;
    $atts['type']  = $type;
    $atts['value'] = $value;
    return geoTag("input", $value, $class, $id, $style, $atts);
}

/**
 * Create an HTML checkbox.
 *
 * @param string $name        Name of value retured by this field
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
 * @return string HTML checkbox
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
        $label = geoLabel(" " . $text, $id, $class);
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
 * @param string       $name  Name of value retured by this field
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
    } elseif ($id===true) {
        $id=null;
    }
   
   
    return geoInput('submit', $name, $value, $atts, $id, $class, $style);
}

/**
 * Create an HTML image submit button.
 *
 * @param string       $src   Image path
 * @param string       $name  Name of value returned by this field
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
 * @param string       $value Value returned by this field
 * @param string       $id    Id Attribute
 * @param string       $class Class Attribute
 * @param array|string $atts  Any other atts. If string, will be used as "onclick" attribute
 * @param string       $name  Name of value retured by this field
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
function geoLabel($text, $for, $class = null, $id = null)
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
 * @param array   $itemClasses Array of classes for each list item
 * @param string  $style       List Style
 * @param string  $lt          List type (either ul or ol) Default is ul
 * @param array   $itemStyles  Array of styles for each list item
 * @param array   $listItemIds Array of ids for each list item
 *
 * @return string End tag
 */
function geoList(
    $lists = null,
    $class = null,
    $id = null,
    $cols = 1,
    $itemClasses = null,
    $style = null,
    $lt = 'ul',
    $itemStyles = null,
    $listItemIds = null
) {
  
    if (!geoIsMultiArr($lists) && is_array($class)) {
        $itemClasses = $class;
        $class="";
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
 * @return object GeoTable Object
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
function geovar($variable, $name = "Variable", $isHtml = null)
{
    echo GeoDebug::trace($name, null, true, true);
    echo GeoDebug::vr($variable, $name, $isHtml);
    
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
    return GeoDebug::db($variable, $name, $addBacktrace, $return, $noHighlight, $always, 1);
}
