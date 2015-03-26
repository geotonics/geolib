<?php
/**
* Document for viewing session variables as debugging
 *
 * PHP Version 5.6
 *
 * @category GeolibFile
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @link     http://geotonics.com/#geolib
*/
require "geolib.php";
geoDb($_SESSION, 'session');
echo geoHtml();
