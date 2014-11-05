<?php
/**
 * Document for class GeoDebug
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
 * Class for static debugging functions
 *
 * @category Debugging
 * @package  Geolib
 * @author   Original Author <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class GeoDebug
{
    /**
     * Initialize Debug session
     * @return void
     */
    public static function init()
    {
        // initialize variables if they don't exist.
        Geo::Val($_SESSION['geoIsDebugSession']);
        Geo::Val($_SESSION['geoOrigIsDebugSession']);
        Geo::Val($_SESSION['geoSaveDebugVars']);
        Geo::Val($_SESSION['geoDbOut']);
        Geo::Val($_SESSION["geoDebugErrors"], array());
        Geo::Val($_SESSION["geoDebugVars"], array());
        
        // Add previously generated error messages to geolib debugging messages.
        // This is useful if you want to display errors from before gealib is included.
        // Add previous errors like this: $_SESSION["geoDebugErrors"]["debug name"]="Debug content";
        if ($_SESSION['geoDebugErrors']) {
            foreach ($_SESSION['geoDebugErrors'] as $key => $value) {
                self::db($value, $key);
            }
        }
        
        if (self::isOn()) {
            ini_set('display_errors', 1);

            // report all errors
            error_reporting(E_ALL);

            //set error handler
            set_error_handler(array("GeoDebug", "customError"), E_NOTICE | E_WARNING | E_STRICT);

            // collect REQUEST variables for debugging
            $requestVariables=self::req();
            if ($requestVariables) {
                self::db(self::req(), "REQUEST VARIABLES for " . $_SERVER["PHP_SELF"], null, null, true);
            }

        }
        
    }
    
    
    /**
     * Error handler function
     *
     * @param int    $errno  Error number
     * @param string $errstr Error string
     * @param string $file   File where error takes place
     * @param string $line   Line where error takes place
     *
     * @return void.
     */
    public static function customError($errno, $errstr, $file, $line)
    {
        
        if (error_reporting() === 0) {
            // continue script execution, skipping standard PHP error handler
            return null;
        }
        
        switch($errno){
            case E_WARNING:
                $level="Warning";
                break;
            case E_NOTICE:
                $level="Notice";
                break;
            case E_STRICT:
                $level="Strict Standards";
                break;
        }
        
        self::db(
            div(
                array($level.": ".$errstr, $file, b("line ".$line)),
                null,
                null,
                "background:#F8C498"
            ),
            true,
            null,
            null,
            true
        );
    }

    
    /**
     * Start or end debugging session by setting debugging flag
     *
     * @param bool $end  End debugging session. Default is start debugging system.
     * @param bool $temp Start temporary debugging and reset debugging flag to original value upon output
     *
     * @return void.
     */
    public static function debugging($end = null, $temp = null)
    {
        if ($temp) {
            $_SESSION['geoOrigIsDebugSession'] = $_SESSION['geoIsDebugSession'];
        }
        if ($end) {
            $_SESSION['geoIsDebugSession']=null;
        } else {
            $_SESSION['geoIsDebugSession'] = true;
        }
    }
    
    /**
     * Formats debugging variables for display
     *
     * @param mixed  $variable Any type of variable being debugged
     * @param string $name     Name used for displaying debug variable
     * @param bool   $isHtml   Display strings as html. Default displays html highlited tags.
     *
     * @return html debug content - is formatted with html
     */
    public static function vr(
        $variable = "<span style='color:red'>DEBUG</span>",
        $name = "Variable",
        $isHtml = null
    ) {
        $result='';
        if (is_array($variable) || is_object($variable)) {
            $size = sizeof($variable);
            $variable = "<pre>" . print_r($variable, true) . "</pre>";
            if (isset($name)) {
                $result.="<h4>" . $name . ":" . $size . "</h4>" . $variable;
            }
        } else {
            if (!$isHtml) {
                $variable= highlight_string($variable." ", true);
            }
            
            $result.= "<div>".geoif($name, "<b>" . $name . ":</b>"). $variable . "</div>";
        }
        return $result;
    }
   
     /**
     * Adds content to debugging array
     *
     * @param mixed  $variable     Variable being debugged
     * @param string $name         Name used for displaying debug variable
     * @param bool   $addBacktrace Determines whether to add backtrace to debug display
     * @param bool   $return       Determines whether debugging content is displayed
     *                                 or saved. Default is saved.
     * @param bool   $noHighlight  Display strings as html. Default displays html tags.
     * @param bool   $always       Always debug, even when not in debugging mode
     * @param int    $traceLevel   Indicates which part of the backtrace array to display
     *
     * @return html debug content is formatted with html
     */
    public static function db(
        $variable = null,
        $name = "debug",
        $addBacktrace = null,
        $return = null,
        $noHighlight = null,
        $always = true,
        $traceLevel = 0
    ) {
    
        if (self::isOn() || $always) {
            if (!$return && $_SESSION['geoDbOut']) {
                $return=$_SESSION['geoDbOut'];
            }
            
            $backtrace  = debug_backtrace();
            
            if ($name===true) {
                $isTrace=true;
                
                if (isset($backtrace[$traceLevel+2])) {
                    $name=$backtrace[$traceLevel+2]["function"]."()";
                } else {
                    $name='no function';
                }
                
            } else {
                $isTrace=null;
            }
        
            $debug='';
            
            // One line for primary backtrace
            if (!$isTrace) {
                $debug.="<div><b>".
                    $backtrace[$traceLevel]['file'].
                    ' line '.
                    $backtrace[$traceLevel]['line'] .
                    "</b></div>";
            }
            
            $debug.= self::vr($variable, $name, $noHighlight);
                
            if ($addBacktrace) {
                // Clean up extended backtrace
                if (sizeof($backtrace)>1) {
                    unset($backtrace[$traceLevel]);
                    
                    foreach ($backtrace as $key => $trace) {
                        unset($backtrace[$key]["object"]);
                    }
                    
                    $debug.= self::vr($backtrace, $name . ' Backtrace');
                }
            
            }
            
            switch ($return) {
                case 1:
                     echo $debug;
                    break;
                case 2:
                    return $debug;
                    break;
                default:
                    $debug = "<div style='background:#E8E8E8;color:#000000; padding:6px 12px'>".
                    $debug.
                    "</div>";
                    $_SESSION['geoDebugVars'][] = $debug;
                    break;
            }
        }
    }

    /**
     * Return boolean or other content based on debug mode
     *
     * @param mixed $true  Value to be returned in debug mode. Default is true
     * @param mixed $false Value to be returned in non debug mode Default is null
     *
     * @return mixed result value
     */
    public static function isOn($true = true, $false = null)
    {
        if ($_SESSION['geoIsDebugSession']) {
            return $true;
        }
        return $false;
    }
    
    /**
     * Reset Debug session: If temporary session is in progress, return to original debug setting, clean debug flags
     *
     * @return mixed result value
     */
    public static function reset()
    {
        if ($_SESSION['geoOrigIsDebugSession']) {
            $_SESSION['geoIsDebugSession'] = $_SESSION['geoOrigIsDebugSession'];
        }
        $_SESSION['geoOrigIsDebugSession']=null;
        $_SESSION['geoSaveDebugVars']=null;
    }

    /**
     * Debug variables are deleted by default when an html page is printed.
     * If this is added to a page, debug variables are saved and added to the next page. (useful for ajax pages)
     *
     * @param bool $startFromHere Determines whether to delete exising debugging at this point
     *
     * @return void.
     */
    public static function saveDebugVars($startFromHere = null)
    {
        if ($startFromHere) {
            unset($_SESSION['geoDebugVars']);
        }
        if (self::isOn()) {
            $_SESSION['geoSaveDebugVars'] = true;
        }
    }
    
    /**
     *  Collect $_POST, $_GET and $_FILES variables for display in debugging
     *
     * @param bool $print  Determines whether to print or return result. Default is to return Result
     * @param bool $always Determines whether to return results even when there are no values.
     *
     * @return html Display of results.
     */
    public static function req($print = null, $always = null)
    {
        $result = '';
        if ($_POST) {
            $result .= self::vr($_POST, '$_POST');
        }
        
        if ($_GET) {
            $result .= self::vr($_GET, '$_GET');
        }
        
        if ($_FILES) {
            $result .= Geo::self($_FILES, '$_FILES');
        }
        
        if ($result || $always) {
            if (!$result) {
                $result = div("NO REQUEST VARIABLES");
            }
            
            $result .= div(time());
            
            if ($print) {
                echo $result;
            } else {
                return $result;
            }
        }
    }
    
    /**
     * Creates full trace or selected trace level
     *
     * @param string $name   Identifies trace in debugging output
     * @param int    $level  Limits trace to a single level of backtrace
     *                   Default is level 1
     *                   If true, return entire backtrace
     * @param bool   $return Determines whether to print out or add to debugging
     *
     * @return text|array One line, or array if returning entire backtrace
     */
    public static function trace($name = null, $level = 1, $return = null)
    {
        $backtrace = debug_backtrace();
        $line=$backtrace[0]['file'] .
            " line " .
            $backtrace[0]['line'];
        if (!isset($level)) {
            $level = 1;
        }
        
        if ($level === true) {
            $traceLine = $backtrace;
            $traceLine[0] = $line;
        } else {
            $line2=$backtrace[$level]['file'].' line ' . $backtrace[$level]['line'] ;
            if (isset($backtrace[$level + 1])) {
                $line2.= "::" . $backtrace[$level + 1]['function']."()";
            }
            $traceLine = div(
                array(
                    $line2,
                    "Traced from:".$line,
                )
            );
        }
        
        if ($return) {
            return $traceLine;
        }
        
        if ($name!==true) {
            $name.=" trace";
        }
        
        self::db($traceLine, $name, null, null, true);
    }

    /**
     * Writes or appends string to a file. Usefull for logging debugging variables.
     *
     * @param string $content   Content to write
     * @param string $title     Name used to identify content in log file.
     * @param bool   $alwaysLog Always log to file. Default is to only log when in debug mode.
     * @param string $mode      Mode indicator for PHP fwrite. Usually either "a" for append or "w" for write.
     *                              Appends by default
     * @param string $fileName  Name of file to write to.
     * @param string $theFile   Name of file generating the log.
     *
     * @return bool Indicates write success or failure
     */
    public static function logToFile(
        $content,
        $title = "log",
        $alwaysLog = null,
        $mode = "a",
        $fileName = "geoLog.htm",
        $theFile = null
    ) {
        
        if (self::isOn() || $alwaysLog) {
            if (defined("GEOBASE")) {
                $fileName = GEOBASE . $fileName;
            }
            
            if ($mode === true) {
                $mode = 'w';
            }
            
            if ($theFile) {
                if ($theFile === true) {
                    $content .= self::vr(debug_backtrace(), 'backtrace');
                } else {
                    $title = $theFile . "<br/>" . $title;
                }
            }
            
            $content = self::vr($content, $title, true);
            $debug   = Geo::writeToFile($fileName, $content, $mode, true);
            return $debug;
        }
    }
    
     /**
     * Dump debug variables in $_SESSION['geoIsDebugSession']
     *
     * @param int  $height     Layout height
     * @param int  $width      Layout width
     * @param bool $dontDelete Don't delete debug variables
     * @param text $style      Added styles
     *
     * @return html
     *
     * Debug variables are deleted by default after they are dumped
     */
    public static function vars(
        $height = null,
        $width = null,
        $dontDelete = null,
        $style = ''
    ) {
        //geo::trace(true);
        if ($_SESSION['geoIsDebugSession']) {
            $result='';
            if (geo::Val($_SESSION['geoDebugVars'])) {
                if (!$style) {
                    $style = "margin:1em 0; overflow:auto;clear:both;";
                    if ($height) {
                        $style .= ' height:' . $height . "px;";
                    }
                    if ($width) {
                        if ($width === true) {
                            $style .= " width:100%";
                        } else {
                            $style .= ' width:' . $width . "px;";
                        }
                    }
                }
                $result = div(
                    "\n      ".
                    // Javascript link to close debug area
                    geoJsLink(
                        'X',
                        null,
                        array(
                            "onclick" => "this.parentNode.style.display = 'none';"
                        ),
                        'emptyParent floatRight',
                        'Close Debug Area'
                    )."\n      ".div($_SESSION['geoDebugVars'], 'geodb', null, 'margin:.5em;'),
                    'geoDebugVars',
                    null,
                    "text-align:left; background:#fff; border:solid 1px #C8C8C8; z-index:99;". $style
                );
            }
            
            if (!$dontDelete && !$_SESSION["geoSaveDebugVars"]) {
                unset($_SESSION['geoDebugVars']);
                 unset($_SESSION['geoDebugErrors']);
            }
            return $result;
        }
    }
}
