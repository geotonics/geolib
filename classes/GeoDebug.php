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
        
        if (!Geo::session("saveDebugVars")) {
            Geo::setSession('debugVars');
        }
        
        // Add previously generated error messages to geolib debugging messages.
        // This is useful if you want to display errors from before ge0lib is included.
        // Add previous errors like this: $_SESSION["geolib"]["geoDebugErrors"]["debug name"]="Debug content";
        $geoDebugErrors=Geo::session("geoDebugErrors");

        if ($geoDebugErrors) {
            foreach ($geoDebugErrors as $key => $value) {
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
     * This function is depreciated. Use Geodebug::debug() instead. 
     * Start or end debugging session by setting debugging flag
     *
     * @param bool $end  End debugging session. Default is start debugging.
     * @param bool $temp Start temporary debugging and reset debugging flag to original value upon output
     *
     * @return void.
     */
    public static function debugging($end = null, $temp = null)
    {
        $isDebugSession=Geo::session("isDebugSession");

        if ($temp) {
            Geo::setSession('origIsDebugSession', $isDebugSession);
        }
       
        Geo::setSession('isDebugSession', !$end);
    }
    
    /**
     * Start or end debugging session by setting debugging flag
     *
     * @param bool $start  Start debugging session. Default is end debugging 
     * @param bool $temp Start temporary debugging and reset debugging flag to original value upon output
     *
     * @return void.
     */
    public static function debug($start = null, $temp = null)
    {
        $isDebugSession=Geo::session("isDebugSession");

        if ($temp) {
            Geo::setSession('origIsDebugSession', $isDebugSession);
        }

        Geo::setSession('isDebugSession', $start);
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
     * @param mixed  $variable        Variable being debugged
     * @param string $name            Name used for displaying debug variable
     * @param bool   $addBacktrace    Determines whether to add backtrace to debug display
     * @param bool   $return          Determines whether debugging content is displayed
     *                                    or saved. Default is saved.
     * @param bool   $noHighlight     Display strings as html. Default displays html tags.
     * @param bool   $always          Always debug, even when not in debugging mode
     * @param int    $traceLevel      Indicates which part of the backtrace array to display
     * @param text   $userSessionName User session name
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
        $traceLevel = 0,
        $userSessionName = "default"
    ) {

        if (self::isOn() || $always) {
            $dbOut=Geo::session('dbOut');
            if (!$return && $dbOut) {
                $return=$dbOut;
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
                $debug.="<div>".
                    $backtrace[$traceLevel]['file'].
                    ' line '.
                    $backtrace[$traceLevel]['line'] .
                    "</div>";
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
                    Geo::setSession(null, $debug, 'debugVars', $userSessionName);
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
        if (Geo::session('isDebugSession')) {
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
        $origIsDebugSession=Geo::session("origIsDebugSession");
        if ($origIsDebugSession) {
            Geo::setSession('isDebugSession', $origIsDebugSession);
        }
        Geo::setSession("origIsDebugSession");
        Geo::setSession("saveDebugVars");
        Geo::setSession("debugVars");
        
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
            Geo::setSession('debugVars');
        }
        if (self::isOn()) {
            Geo::setSession('saveDebugVars', true);
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
            $result .= self::vr($_FILES, '$_FILES');
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
     * @param string $name          Identifies trace in debugging output
     * @param int    $level         Limits trace to a single level of backtrace
     *     Default is level 1
     *     If true, return entire backtrace
     * @param bool   $return        Determines whether to print out or add to debugging
     * @param bool   $dontTraceFrom Don't add a one line trace to the location of the calling function.
     *
     * @return text|array One line, or array if returning entire backtrace
     */
    public static function trace($name = null, $level = 1, $return = null, $dontTraceFrom = null)
    {
        $backtrace = debug_backtrace();
        
        if (!isset($level)) {
            $level = 1;
        }
        
        $upLevel=$level-1;
        
        if(isset($backtrace[$level]['class'])){
            $fromClass=$backtrace[$level]['class']." ".$backtrace[$level]['function'].": ";
        } else {
            $fromClass="";
        }
        
        $tracedFrom="Traced from ".$fromClass.$backtrace[$upLevel]['file'] .
        " line " .
        $backtrace[$upLevel]['line'];
        
        if ($level === true) {
            $traceLine = $backtrace;
            $traceLine[$upLevel]=$tracedFrom;
        } else {
            $trace="";
            
            if (isset($backtrace[$level + 1])) {
                $trace.= $backtrace[$level + 1]['function']."()::";
            }
            
            $trace.=$backtrace[$level]['file'].' line ' . $backtrace[$level]['line'];
            $traceArr=array($trace);
            
            if (!$dontTraceFrom) {
                $traceArr[]=$tracedFrom;
            }
            
            $traceLine = div($traceArr);
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
     * Returns a simple one line trace to the next trace level
     *
     * @param string $name Optional name to mark the trace with
     *
     * @return string one line trace
     */
    public static function miniTrace($name = "miniTrace")
    {
        $backtrace = debug_backtrace();
        return $name.":".$backtrace[1]['file'] .
            " line " .
            $backtrace[1]['line']."<br>";
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
     * Dump debug variables in $_SESSION['geolib']['isDebugSession']
     *
     * @param int  $height          Layout height
     * @param int  $width           Layout width
     * @param bool $dontDelete      Don't delete debug variables
     * @param text $style           Added styles
     * @param test $userSessionName Name of user session
     *
     * @return html
     *
     * Debug variables are deleted by default after they are dumped
     */
    public static function vars(
        $height = null,
        $width = null,
        $dontDelete = null,
        $style = '',
        $userSessionName = null
    ) {
       
        //geo::trace(true);
        if (Geo::session('isDebugSession')) {
            $result='';
            if (!$userSessionName) {
                $userSessionName="default";
            }
            
            //geoVar($_SESSION,'thesession');
            $debugVars=Geo::session($userSessionName, 'debugVars');
            
            //geoVar($debugVars,'debugVars');
            if ($debugVars) {
                
                if (!$style) {
                    $style = "margin:1em 0;";
                }
                
                $style.=" overflow:auto;clear:both;";
                
                if ($height) {
                    $style .= ' max-height:' . $height . "px;";
                }
                
                if ($width) {
                    if ($width === true) {
                        $style .= " width:100%";
                    } else {
                        $style .= ' width:' . $width . "px;";
                    }
                }
                
                
                $result = div(
                    "\n      ".
                    // Javascript link to close debug area
                    geoJsLink(
                        geoImg(GEO_URI."images/redx.png"),
                        null,
                        'Close Debug Area',
                        "closeDebugArea",
                        array(
                            "style"=>"float:right;cursor:pointer;",
                            "onclick" => "this.parentNode.style.display = 'none';"
                        )
                    )."\n      ".div($debugVars, null, null, null, array("style"=>'margin:.5em .5em 1em .5em;',"class"=>"geodb")),
                    null,
                    null,
                    null,
                    array(
                        "style"=>"text-align:left; background:#fff; border:solid 1px #C8C8C8; z-index:99; position:relative;". $style,
                        "class"=>"debugVars"
                    )
                );
            }
            
            if (!$dontDelete && !Geo::session("saveDebugVars")) {
                Geo::setSession('debugVars');
                Geo::setSession('geoDebugErrors');
            }
            
            return $result;
        }
    }
}
