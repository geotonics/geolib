<?php
/**
* Geolib intitiation
 *
 * PHP Version 5.6
 *
 * @category GeolibFile
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @link     http://geotonics.com/#geolib

Copyright 2015 Peter Pitchford

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
// Initialize user added site specific variables

$dirname=dirname(__FILE__);
if (file_exists($dirname."/config.php")) {
    include $dirname."/config.php";
} else {
    include $dirname."/defaultConfig.php";
}


// Start session if it hasn't already been started
if (GEO_START_SESSION) {
    if ((function_exists("session_status") && session_status() == PHP_SESSION_NONE) || !session_id()) {
        session_start();
    }
}

// Include wrapper functions
require "functions.php";
spl_autoload_register('geolib_autoloader');
GeoDebug::init();
GeoHtml::setDoctype();
//geoVar($_SERVER,'server');