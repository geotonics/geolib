<?php
/**
 * Document for class GeoCell
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
* Geo - Class to create HTML table cell tags
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/

class GeoCell extends GeoTag
{
    /**
     * Class constructor
     *
     * @param string $text    Content of table cell
     * @param int    $colspan Number of columns this table cell will span
     * @param int    $rowspan Number of rows this tble cell will span
     * @param string $class   class of table cell
     * @param string $valign  valign of table cell
     * @param string $style   style of table cell
     * @param string $id      id of table cell
     */
    public function __construct(
        $text = null,
        $colspan = null,
        $rowspan = null,
        $class = null,
        $valign = null,
        $style = null,
        $id = null
    ) {
        $this->init('td', $text, $class, $id, $style);
        $this->setRowSpan($rowspan);
        $this->setAtt("colspan", $colspan);
        $this->setAtt("valign", $valign);
    }
    
    /**
     * Set number of rows to span
     *
     * @param string $rowspan number of rows to span
     *
     * @return void
     */
    public function setRowSpan($rowspan)
    {
        $this->atts['rowspan'] = $rowspan;
    }
    
    /**
     * Returns HTML table cell tag
     *
     * @param string $text Content of table cell
     * @param string $id   id of table cell
     *
     * @return html table cell tag
     */
    public function tag($text = null, $id = null)
    {
        $this->setText($text);
        $this->setAtt("id", $id);
        return parent::baseTag();
    }
}
