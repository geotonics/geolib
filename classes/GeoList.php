<?php
/**
 * Document for class GeoList
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
* Geo - Class to create html list (ul or ol) tags
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/


class GeoList extends GeoTag
{
    private $items;
    private $cols;
    private $addLinks;
    /**
     * Construcor
     *
     * @param array||string $items    List item(s)
          To have ids for each list item, either:
              Easiest way: make item keys non integer, such as "item_3"
              Otherwise:feed id array to ->tag()
     * @param string        $class    Class Attribute
     * @param string        $id       Id Attribute
     * @param integer       $cols     Number of columns. Break $items into this many equal (or nearly equal) parts.
         Each part becomes an HTML list.
     * @param string        $style    Style attribute
     * @param string        $listType Type of list (ol or ul)
     */
    public function __construct($items = null, $class = null, $id = null, $cols = 1, $style = null, $listType = 'ul')
    {
        if (!$listType) {
            $listType = 'ul';
        }
        
        $this->cols = $cols;
        $this->init($listType, null, $class, $id, $style);
        $this->items = Geo::arr($items);
    }
    
    /**
     * Sets flag to indicate that each item in the list will be a link with the key as text
     *
     * @return void
     */
    public function addLinks()
    {
        $this->addLinks = true;
    }
    
    /**
     * Create HTML list tag
     *
     * @param string|array $classes Class attribute(s)
     * @param string|array $ids     Id attribute(s)
     * @param string|array $styles  Style attribute(s)
     *
     * @return void
     */
    public function tag($classes = null, $ids = null, $styles = null)
    {
        if ($this->cols) {
            $itemarrs = Geo::arraySplit($this->items, $this->cols);
        } else {
            $itemarrs[] = $this->items;
        }
        
        if ($itemarrs) {
            foreach ($itemarrs as $key => $items) {
                $this->text[$key] = '';
                
                foreach ($items as $key2 => $item) {
                    if (!is_object($item)) {
                        if ($this->addLinks) {
                            $item = geoLink($item, $key2);
                        }
                        
                        if (is_int($key2)) {
                            $id = Geo::ifArr($ids, $key2);
                        } else {
                            $id = $key2;
                        }
                        
                        $item = new GeoTag("li", $item, Geo::ifArr($classes, $key2), $id, Geo::ifArr($styles, $key2));
                    }
                    $this->text[$key] .= $item->baseTag();
                }
                
            }
            
        }
        return parent::baseTag();
    }
}
