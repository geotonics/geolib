<?php

/**
 * Document class Geo
 *
 * PHP Version 5.6
 *
 * @category Class
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @link     http://geotonics.com/#geolib
 */
 
/**
* Geo - Class for static utility functions
*
 * @category Utility
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class Geo
{
   
    
    /**
     * Checks email address for valid syntax.
     *
     * @param string $email Email address
     *
     * @return bool
    */
    public static function isValidEmail($email)
    {  
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        return preg_match($regex, $email);
    }
    
    /**
     * Case-insensitive str_replace()
     *
     * @param string $find    Text to replace
     * @param string $replace Replacement
     * @param string $string  Text to search
     *
     * @return string Updated text
    */
    public static function strReplace($find, $replace, $string)
    {
        
        $parts = explode(strtolower($find), strtolower($string));
        $pos   = 0;
        foreach ($parts as $key => $part) {
            $parts[$key] = substr($string, $pos, strlen($part));
            $pos += strlen($part) + strlen($find);
        }
        return (join($replace, $parts));
    }
    
    /**
     * Prepares text and converts it into html.
     *
     * @param string $txt             Text to convert
     * @param bool   $addHtmlEntities Add html entities
     *
     * @return html
    */
    public static function textToHtml($txt, $addHtmlEntities = null)
    {
        
        // Transforms txt in html
        //Kills double spaces and spaces inside tags.
        while (!(strpos($txt, '  ') === false)) {
            $txt = str_replace('  ', ' ', $txt);
        }
        
        $txt = str_replace(' >', '>', $txt);
        $txt = str_replace('< ', '<', $txt);
        if ($addHtmlEntities) {
            //Transforms accents in html entities.
            $txt = htmlentities($txt);
            
            //We need some HTML entities back!
            $txt = str_replace('&quot;', '"', $txt);
            $txt = str_replace('&lt;', '<', $txt);
            $txt = str_replace('&gt;', '>', $txt);
            $txt = str_replace('&amp;', '&', $txt);
        }
        /*
        //Ajdusts links - anything starting with HTTP opens in a new window
        $txt = geoStrReplace("<a href=\"http://", "<a target=\"_blank\" href=\"http://", $txt);
        $txt = geoStrReplace("<a href=http://", "<a target=\"_blank\" href=http://", $txt);
         */
        
        //Basic formatting
        $eol  = (strpos($txt, "\r") === false) ? "\n" : "\r\n";
        $txt  = trim($txt);
        $html = '<p>' . str_replace("$eol$eol", "</p><p>", $txt) . '</p>';
        $html = str_replace("$eol", "<br />", $html);
        $html = str_replace("</p>", "</p>", $html);
        
        $html = str_replace("<p></p>", "<p>&nbsp;</p>", $html);
        
        //Wipes <br> after block tags (for when the user includes some html in the text).
        $wipebr = array(
            "table",
            "tr",
            "td",
            "blockquote",
            "ul",
            "ol",
            "li"
        );
        for ($x = 0; $x < count($wipebr); $x++) {
            $tag  = $wipebr[$x];
            $html = Geo::strReplace("<$tag><br />", "<$tag>", $html);
            $html = Geo::strReplace("</$tag><br />", "</$tag>", $html);
        }
        
        return $html;
    }
    
    /**
     * Writes content to file.
     *
     * @param string $filename Name of file to write to
     * @param string $content  Content to write to file
     * @param string $mode     Mode for PHP fwrite
     *
     * @return string Message with status of write
    */
    public static function writeToFile($filename, $content, $mode = 'w')
    {
        $content = Geo::arr($content);
        $content = implode("\t", $content) . "\n";
        if (is_writable($filename)) {
            if ($handle = fopen($filename, $mode)) {
                if (fwrite($handle, $content) === false) {
                    $feedback = "Cannot write to file (" . $filename . ")";
                } else {
                    $feedback = "Success, wrote content to file (" . $filename . ")";
                }
                fclose($handle);
            } else {
                $feedback = "Cannot open file (" . $filename . ")";
            }
            
        } else {
            if (file_exists($filename)) {
                            $feedback = "The file " . $filename . " is not writable";
            } else {
                // try to create file
                    $handle = fopen($filename, "w");
                    fclose($handle);
                if (file_exists($filename)) {
                    $feedback=self::writeToFile($filename, $content, $mode);
                    
                } else {
                    $feedback = $filename . " does not exist anc could not be created";
                }
            }
        }
        return $feedback;
    }

    
    /**
     * Naturally sorts a Two dimensional array
     *
     * @param array $arrayInput Array to be sorted passed by reference
     *
     * @return void
    */
    public static function natSort2d(&$arrayInput)
    {
        $arrayTemp = $arrayOut = array();
        
        foreach ($arrayInput as $key => $value) {
            reset($value);
            $arrayTemp[$key] = current($value);
        }
        
        natsort($arrayTemp);
        
        foreach ($arrayTemp as $key => $value) {
            $arrayOut[] = $arrayInput[$key];
        }
        
        $arrayInput = $arrayOut;
    }
    
     /**
     * Parses string to find the first words or first paragraph
     *
     * @param string $string       String to parse for first words
     * @param int    $numWords     Number of words to return. If true, return first paragraph
     * @param bool   $returnString Return first words, otherwise return an array of parts.
     *
     * @return mixed Either first words (plus ellipsis if text has been abbreviated), or array of parts
     *    Default is array of parts
     *    If parts, array[0] is the first $numWords words, array[1] is the rest of the text.
    */
    public static function firstWords($string, $numWords, $returnString = null)
    {
        $string = strip_tags($string);
        $parts  = explode("\n", $string, 2);
        //GeoDebug::db($parts, 'origparts');
        // divide on first paragraph
        if ($numWords === true) {
            return $parts;
        } else {
            // divide on first $num words into an array
            $words = explode(' ', $parts[0]);
            $size  = sizeof($words);
            
            if ($size < $numWords+1) {
                if ($returnString) {
                    return $parts[0];
                }
                return $parts;
            }
            
            $parts2[] = implode(" ", array_slice($words, 0, $numWords));
            
            if (isset($parts[1])) {
                $parts2[] = $parts[1];
            }
            
            $parts2[] = implode(" ", array_slice($words, $numWords));
            
            if ($returnString) {
                return $parts2[0]."...";
            }
            
            return $parts2;
        }
    }

   
    
    /**
     * Sets default value if value is not already set
     *
     * @param mixed $var     Variable to be set if not already set.
     * @param mixed $default Value to be given to variable if it is not already set.
     *
     * @return mixed value
    */
    public static function val(&$var, $default = null)
    {
        if (!isset($var) && $default !== null) {
            $var = $default;
        }
        return $var;
    }
    
    /**
    * Creates tabs for source code or html
     *
     * @param int  $tabs Number of tabs to create
     * @param bool $text Content of tabs. Default is 3 text spaces.
     *   If true, content is 3 html spaces
     *
     * @return text|html
     */
    public static function geoTabs($tabs = 1, $text = "   ")
    {
        if ($text === true) {
            $text = "&nbsp;&nbsp;&nbsp;";
        }
        $tab = '';
        for ($i = 0; $i < $tabs; $i++) {
            $tab .= $text;
        }
        return $tab;
    }
    
    
    /**
     * Explodes string on given separator, returns given index of the resulting array.
     *
     * @param string $val   Value to explode
     * @param string $index Index of array value to return. Default is 1
     * @param string $sep   Separator to explode on
     *
     * @return string $arr[$index] index of array
     */
    public static function exp($val, $index = 1, $sep = "_")
    {
        $arr = explode($sep, $val);
        if (isset($arr[$index])) {
            return $arr[$index];
        }
    }

    /**
     * Sets session variables. Optionally add name of session array
     *
     * @param string $name         Name of session variable
     * @param string $value        Value of session variable
     * @param string $subArrayName Name of session sub array.
     * @param string $arrayName    Name of session array. Default is "geolib"
     *
     * @return void
     */
    public static function setSession($name, $value = null, $subArrayName = null, $arrayName = GEO_INSTANCE)
    {
        if ($arrayName) {
            if ($subArrayName) {
                if ($name) {
                    if ($value) {
                        $_SESSION[$arrayName][$subArrayName][$name]=$value;
                    } else {
                        unset($_SESSION[$arrayName][$subArrayName][$name]);
                    }
                } else {
                    if ($value) {
                        $_SESSION[$arrayName][$subArrayName][]=$value;
                    } else {
                        unset($_SESSION[$arrayName][$subArrayName]);
                    }
                }
            } else {
                if ($name) {
                    if ($value) {
                        $_SESSION[$arrayName][$name]=$value;
                    } else {
                        unset($_SESSION[$arrayName][$name]);
                    }
                } else {
                    if ($value) {
                        $_SESSION[$arrayName][]=$value;
                    } else {
                        unset($_SESSION[$arrayName]);
                    }
                }
            }
        } elseif ($name) {
            if ($value) {
                $_SESSION[$name]=$value;
            } else {
                unset($_SESSION[$name]);
            }
        }
    }
    
    /**
     * Gets session variables.
     *
     * @param string $name         Name of session variable
     * @param string $subArrayName Name of session sub array.
     * @param string $arrayName    Name of session array. Default is "geolib"
     *
     * @return void
     */
    public static function session($name, $subArrayName = null, $arrayName = GEO_INSTANCE)
    {
       
        if ($arrayName) {
            if ($subArrayName) {
                if (isset($_SESSION[$arrayName][$subArrayName][$name])) {
                    return $_SESSION[$arrayName][$subArrayName][$name];
                }
            } elseif (isset($_SESSION[$arrayName][$name])) {
                return $_SESSION[$arrayName][$name];
            }
        } elseif ($name) {
            if (isset($_SESSION[$name])) {
                return $_SESSION[$name];
            }
        }
    }
    
    /**
     * Sends email with default parameters.
     *
     * @param string $text    Email content
     * @param string $subject Email subject
     * @param string $address Email to address
     * @param string $from    Email from address
     *
     * @return bool success or failure form php mail()
     */
    public static function email($text = "debug", $subject = "debug", $address = null, $from = null)
    {
        if (!$address) {
            $address = GEO_DEFAULT_EMAIL;
        }
        
        if (!$from) {
            $from = GEO_DEFAULT_FROM;
        }
        
        if (is_array($text) || is_object($text)) {
            $text = print_r($text, true);
        }
        
        $headers = 'From: ' . $from . PHP_EOL;
        $headers .= 'Content-type: text/plain; charset=utf-8' . PHP_EOL;
        $retpath1 = '-f' . $from;
        return mail($address, $subject, $text, $headers, $retpath1);
    }

    /**
     * Split array into equal pieces
     *
     * @param array $arr      Array to be split
     * @param int   $numparts Number of pieces
     *
     * @return array|null Array of pieces or null if no $arr
     */
    public static function arraySplit($arr, $numparts)
    {
        if (!$arr) {
            return null;
        }
        if ($numparts == 1) {
            return array($arr);
        }
        $toparts = count($arr);
        $count   = 0;
        if ($toparts > ($numparts - 1)) {
            $portions = ceil($toparts / $numparts);
            
            for ($a = 1; $a < $numparts + 1; $a++) {
                for ($yz = 0; $yz <= $portions - 1; $yz++) {
                    if (isset($arr[$count]) && $arr[$count]) {
                        $ret[$a - 1][] = $arr[$count];
                    }
                    $count++;
                }
            }
        } else {
            // Already more pieces than requested, split arrays into existing
            //     number of pieces
            return Geo::arraySplit($arr, $toparts);
        }
        return $ret;
    }
    
    /**
     * Formats html. Leaves pre and text area tags alone
     * Eliminates all line breaks and multiple spaces in the html, then adds new tabs and line breaks
     * This function is memory intensive! Only use it for debugging.
     *
     * @param string $html Html to be formatted
     *
     * @return $html Formatted html
    */
    public static function tidy($html)
    {
        if (GeoDebug::isOn()) {
            // don't tidy these tags
            $nonTidys = array(
                'pre',
                'textarea'
            );
            
            $newTidyMatches = array();
            foreach ($nonTidys as $nonTidy) {
                $pattern = "/<" . $nonTidy . ".*?>.*?<\/" . $nonTidy . ">/is";
                preg_match_all($pattern, $html, $nonTidyMatches);
                
                if ($nonTidyMatches[0]) {
                    $newTidyMatches[$nonTidy] = $nonTidyMatches[0];
                    foreach ($newTidyMatches[$nonTidy] as $key => $match) {
                        $html = preg_replace($pattern, "GEO_" . $nonTidy . "_MATCH_" . $key, $html, 1);
                    }
                }
            }
        }
    
        $oneliners  = array('td','dl','h1','h2','h3','h4','option','li');
        $lt         = '';
        $page       = '';
        $html       = preg_replace("/(\040)+/", ' ', $html);
        $html       = preg_replace("/\s+/", ' ', $html);
        $html       = str_replace(array("\n","\r"), " ", $html);
        $html       = str_replace("> <", "><", $html);
        $ntabs      = 0;
        $matches[4] = $html;
        
        // shorter strings must be after strings that include it
        $tags="noscript|script|form|table|param|p|h1|h2|h3|h4|tr|td|html|head|title|".
            "body|div|dd|ol|ul|link|li|blockquote|option|select|meta|object|center";
        
        while (preg_match("{(.*?)(</?(".$tags.").*?>)(.*)}i", $matches[4], $matches)) {
            //geoVar($matches, 'matches');
            
            if ($matches[2][1] == "/") {
                $start = false;
            } elseif ($matches[3] == 'meta' || $matches[3] == 'link' || $matches[3] == 'param') {
                $start = null;
            } else {
                $start = true;
            }
            
            if ($start === false) {
                $ntabs--;
            } else {
                $tt = $matches[3];
            }
            
            if ($matches[1] || $matches[1] === "0") {
                if (!in_array($lt, $oneliners)) {
                    $page .= "\n";
                    
                    if ($start == false) {
                        $page .= geoTabs($ntabs + 1);
                    } else {
                        $page .= geoTabs($ntabs);
                    }
                }
                $page .= $matches[1];
            }
            
            if (!(in_array($lt, $oneliners) && $lstart == true && in_array($lt, $oneliners) && $start == false)) {
                $page .= "\n";
                $page .= geoTabs($ntabs);
            }
            
            $page .= $matches[2];
            $leftover = $matches[4];
            
            if ($start == true) {
                $ntabs++;
            }
            
            if (in_array($matches[2], $oneliners)) {
                $page .= "\n" . geoTabs($ntabs);
            }
                        $lt     = $tt;
            $lstart = $start;
        }
        
        if ($page) {
            if (GeoDebug::isOn()) {
                if ($newTidyMatches) {
                    foreach ($newTidyMatches as $nonTidy => $matches) {
                        foreach ($matches as $key => $match) {
                            $page = preg_replace("/GEO_" . $nonTidy . "_MATCH_" . $key . "/", $match, $page, 1);
                        }
                    }
                }
            }
            $page .= "\n" . geoTabs($ntabs) . $leftover;
            $page = trim($page);
            return $page;
        } else {
            return $html;
        }
    }
    
    /**
     * Creates array from value if value is not already an array
     *
     * @param mixed $value            Any value
     * @param bool  $returnEmptyArray If not value, instead of returning value in an array
     *                                return an emtpy array;
     *
     * @return array
     */
    public static function arr($value, $returnEmptyArray = null)
    {
        if (is_array($value)) {
            return $value;
        }
        
        if (isset($value) || !$returnEmptyArray) {
            return array($value);
        }
        return array();
    }
    
    /**
     * Returns value from array, or array if array is not an array or key is null
     *
     * @param array      $array Source of value
     * @param string|int $key   Array key which indicates value
     *
     * @return mixed
     */
    public static function ifArr($array, $key = null)
    {
        if (is_array($array) && isset($key)) {
            if (isset($array[$key])) {
                return $array[$key];
            }
            return null;
            
        }
        
        return $array;
    }
    
    /**
     * Turns cents into dollars
     *
     * @param int|float $number        Dollars or cents (default assumes dollars)
     * @param int|bool  $divisor       Devide $number by this int or if $divisor
     *                                 is true or null, just format dollars
     * @param bool      $addDollarSign Optionally add dollar sign to result
     *
     * @return float
     */
    public static function dollars($number, $divisor = 100, $addDollarSign = null)
    {
        if ($divisor === true) {
            $divisor = null;
        }
        if ($divisor) {
            $number /= $divisor;
        }
        
        return geoIf($addDollarSign, "$") . money_format('%n', $number);
    }
    
    /**
     * Direct browser to a different page before any page output
     *
     * @param string $uri target uri. If null, the requested uri without any parameters is used.
     *     If true the requested uri with any parameters is used.
     *
     * @return void, ends current script and redirects page
     * @errors Will cause PHP runtime error if called after any character
     *   has been echoed
     */
    public static function redirect($uri = null)
    {
        if ($uri===true) {
            $uri = $_SERVER['REQUEST_URI'];
        } elseif (!strlen($uri)) {
            $url    = parse_url($_SERVER["REQUEST_URI"]);
            $uri = $url["path"];
            //$uri = $_SERVER['REQUEST_URI'];
        }
        // Redirect browser to $uri page
        header("Location: " . $uri."?asdf");
        
        //this is executed only if header fails and throws warning
        echo "Redirect to $uri ($uri) failed";
        exit;
    }
    
    /**
     * Used with jquery to make tabs
     *
     * @param array $contentArr Contains title and content arrays
     * @param array $tabsClass  Optional class for tabs
     *
     * @return mixed html and jquery to create tabs
     *
     * $contentArr[0] is an array of tab titles
     * $contentArr[1] is an array of tab content
     * If content is multi-dementional, each array is treated the same as
     *   $contentArr to create a nested set of tabs.
     */
    public static function makeTabs($contentArr, $tabsClass = null)
    {
        //GeoDebug::db($contentArr[0], 'contentArr[0]');
        $content = $js = '';
        $titleArr=array();
        foreach ($contentArr[0] as $titleNum => $title) {
            $tabNumber = ($titleNum + 1);
            $tabId     = "tabs-" . $tabNumber;
            
            $titleArr[] = geoLink("#" . $tabId, $title);
            
            if (is_array($contentArr[1][$titleNum])) {
                $js .= "$('#tabs" . $tabNumber . "').tabs();";
                $contentArr[1][$titleNum] = self::makeTabs(
                    $contentArr[1][$titleNum],
                    $tabNumber
                );
            }
           
            $content .= div(
                div($contentArr[1][$titleNum], null, 'tabs' . $tabNumber),
                null,
                $tabId
            );
        }
       
        $js = "$('#tabs').tabs();" . $js;
        return div(
            geoList($titleArr).$content,
            $tabsClass,
            'tabs'
        ).geoScript(true, $js);
    }
    
    /**
     * Creates a css table from a multidimensional array
     *
     * @param array        $rows          A mulitidemensional array. Each row is an array of content
                                              If the row index is non numeric, it will be used as the row id
     * @param array|string $classes       Wrapper Class or classes (in addition to the default wrapper class(es))
     * @param array|string $rowClasses    If $rowClasses is an array each value is a class for a corresponding row.
                                              This will only work is if $rows and $rowClassess have matching indexes.
     *                                    If $rowClasses is a string it becomes the class for all rows
     * @param array|string $rowIds        If $rowIds is an array, each value is an id for a corresponding content row
     *                                    If $rowIds is a string, the id for a corresponding content row is the row
     *                                        key plus "_" plus the string.
     * @param array|string $columnClasses If an array each value is a class for a corresponding content column.
     *                                        If a string it becomes the class for all rows
     * @param string       $tableClass    Default class or classes for wrapper. Default is "cssTable"
     *
     * @return string|html
     */
    public static function cssTable(
        $rows,
        $classes = null,
        $rowClasses = null,
        $rowIds = null,
        $columnClasses = null,
        $tableClass = "cssTable"
    ) {
        if (isset($rows) && $rows) {
            $classes   = Geo::arr($classes, true);
            
            if ($tableClass) {
                $classes[] = $tableClass;
            }
            
            foreach ($rows as $key => $row) {
                $allRows[$key]=div($row, $columnClasses);
            }
            
            return div(div($allRows, $rowClasses, $rowIds), geoIf($classes, implode(' ', $classes)));
            
        }
        
    }
        
    /**
     * Case insensitive version of PHP's in_array
     *
     * @param string $search Variable to test for
     * @param array  $array  Target of case insensitive search
     *
     * @return bool
    */
    public static function inArrayi($search, &$array)
    {
        $search = strtolower($search);
        foreach ($array as $item) {
            if (strtolower($item) == $search) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Allows inline test for value.
     * Same as geoIf, except it can test undefined variables but not constants.
     *
     * @param mixed $var     Variable to be tested for value
     * @param mixed $result1 Value to be returned if test is passed
     * @param mixed $result2 Value to be returns if test fails
     *
     * @return mixed Result of test
    */
    public static function ifVal(&$var, &$result1 = true, &$result2 = null)
    {
        if ($var) {
            return $result1;
        }
        return $result2;
    }
    
    /**
     * Recurve method to get file names from a directory.
     *
     * @param string $dirstr         Directory to get filenames from
     * @param bool   $includeSubDirs Flag to indicate names of files from subdirectories will be included.
     * @param bool   $geoLinks       Flag to indicate that the filenames will be linked to the files.
     * @param array  $skips          Names of files to skip over
     *
     * @return array File names
    */
    public static function fileNames(
        $dirstr = null,
        $includeSubDirs = false,
        $geoLinks = true,
        $skips = array('error_log', 'Thumbs.db', 'Desktop.ini', '.htaccess', '.htpasswd')
    ) {
        
        if ($dirstr) {
            $dirstr .= "/";
        } else {
            $dirstr = "./";
        }
        
        if (is_dir($dirstr)) {
            $fh = opendir($dirstr);
        }
        
        
        if ($fh) {
            $skips = Geo::arr($skips);
            $files = $directories = array();
            
            while (false !== ($filename = readdir($fh))) {
                $prefiles[] = $filename;
            }
            
            natsort($prefiles);
            $prefiles = array_values($prefiles);
            
            foreach ($prefiles as $filename) {
                $dirFile = $dirstr . $filename;
                if ($filename != '.' && $filename != '..' && !in_array($filename, $skips)) {
                    if ($includeSubDirs && is_dir($dirFile)) {
                        $directories[$filename]
                            = self::fileNames($dirstr . $filename, $includeSubDirs, $geoLinks, $skips);
                    } elseif ($geoLinks) {
                            $files[] = geoLink($dirstr . $filename, $filename);
                    } elseif (!is_dir($dirFile)) {
                            $files[] = $filename;
                    }
                }
            }
            closedir($fh);
            
            if ($directories) {
                $files['directories'] = $directories;
            }
            
            return $files;
        }
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
    public static function validText($name, &$values, &$missing, $prefix = null, $printableClass = null)
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
    
    public static function isForEmail($isForEmail=true){
        
        self::setSession("isForEmail",$isForEmail,"page");
    }
    
    public static function reset(){
        
        self::setSession("page");    
    }
}
