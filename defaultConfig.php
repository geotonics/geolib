<?php
/**
* Document for geolib configuration
 * To add configurations to geolib, add the missing values and change the name of this file to "config.php"
 *
 * PHP Version 5.6
 *
 * @category GeolibFile
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @link     http://geotonics.com/#geolib
*/

// user added site specific variables
define("GEO_DEBUG_EMAIL", "");
define("GEO_DEFAULT_EMAIL", "");
define("GEO_DEFAULT_FROM", "");
define("GEO_IMAGE_PATH", "");
define("GEO_DEBUG_PASSWORD", "");

// GEO_BASE can be used for an alternative path to images or other files and can be defined before geolib is included. 
// geoImg checks for GEO_BASE before including an image
if (!defined("GEO_BASE")) {
    define("GEO_BASE", "");
}

// change true to false if you don't want session to start when geolib is loaded 
define("GEO_START_SESSION",true);
