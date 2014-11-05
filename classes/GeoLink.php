<?php

/**
 * Document for class GeoLink
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
* Geo - Class to create HTML a tags
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class GeoLink extends GeoTag
{
    private $target;
    private $onmouseover;
    private $onmouseout;
    
    /**
     * Set attributes of html a tag
     *
     * @param string $link   href path of link
     * @param string $text   Text of link
     *     If text has html tags, then you need to add a (non html) text title so the the title does not have html
     * @param string $class  class of link
     * @param string $title  title of link
     * @param string $target target of link
     * @param string $id     id of link
     * @param string $atts   any other atts of link
     * @param string $style  style of link
     *
     * title is later in the list because its usually the same as $text
     */
    public function __construct(
        $link = null,
        $text = null,
        $class = null,
        $title = null,
        $target = null,
        $id = null,
        $atts = null,
        $style = null
    ) {
        
        $this->setLink($link);
        $this->setAtt("target", $target);
        
        if ($atts && !is_array($atts)) {
            $attsarr              = explode("=", $atts);
            $newatts[$attsarr[0]] = $attsarr[1];
            $atts                 = $newatts;
        }
        $this->init('a', $text, $class, $id, $style, $atts);
        $this->setTitle($title);
        
    }
    
     /**
     * Set href of link
     *
     * @param string $link href of link
     *
     * @return void
     */
    public function setLink($link)
    {
        $this->setAtt('href', $link);
    }

    /**
     * Set title of link
     *
     * @param string $title title of link
     *
     * @return void
     */
    public function setTitle($title)
    {
        
        if (!$title) {
            $title = $this->text;
        } elseif ($title === true) {
            $title = "";
        }
        $this->setAtt('title', htmlentities($title));
        
    }
    
    /**
     * Set text of link
     *
     * @param string $text text of link
     *
     * @return void
     */
    public function setText($text)
    {
        if ($text) {
            $this->text = $text;
        } else {
            $this->text = $this->atts['href'];
        }
    }
    
    /**
     * HTML a tag
     *
     * @param string $link  href of link
     * @param string $text  text of link
     * @param string $title title of link
     *
     * @return html a tag
     */
    public function tag($link = null, $text = null, $title = null)
    {
        if ($title) {
            $this->setTitle($title);
        }
              
        if ($link) {
            $this->setLink($link);
        }
        
        if ($text) {
            $this->setText($text);
        }
        //$this->setTitle();
        return parent::baseTag();
    }
}
