<?php
/**
 * Document for class GeoBody
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
* Geo - Class to create html body tags
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class GeoBody extends GeoTag
{
    private $page;
    private $onload;
    
    /**
     * Constructor for HTML body tag object
     *
     * @param string $page   content of html body
     * @param string $class  class of html body
     * @param string $onload javascript to run upon page load
     * @param bool   $style  style of html body
     * @param id     $id     id of html body
     */
    public function __construct($page, $class = null, $onload = null, $style = null, $id = null)
    {
        $atts['onload'] = $onload;
        parent::__construct(
            "body",
            geoIf(GeoDebug::isOn(), div(null, 'emptyMe', 'debug')) . $page,
            $class,
            $id,
            $style,
            $atts
        );
    }
    
    /**
     * Creates print out body tag
     *
     * @param string $addon Extra html to add to end of body text
     * @param bool   $start Indicates that tag will be start plus content without end tag.
     *
     * @return text html body tag
     */
    public function tag($addon = null, $start = null)
    {
        $this->text .= $addon;
        return parent::baseTag($start);
    }
}
