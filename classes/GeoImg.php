<?php

/**
 * Document for class GeoImg
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
* Geo - Class to create HTML image tags, or an image in a link
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class GeoImg extends GeoTag
{
    private $href;
    private $title;
    private $target;
    private $rel;
    private $absolutePath;
    
    /**
     * Constructor for GeoImg object
     *
     * @param string $src    Image src
     * @param string $alt    Image alt
     * @param string $href   Link href
     * @param string $title  Link title
     * @param string $target Link target
     * @param string $host   Image host
     * @param string $id     Image id
     * @param string $class  Image class
     * @param string $style  Image style
     * @param array  $atts   Any other Image attributes
     * @param string $rel    Link rel
     */
    public function __construct(
        $src = null,
        $alt = null,
        $href = null,
        $title = null,
        $target = null,
        $host = null,
        $id = null,
        $class = null,
        $style = null,
        $atts = null,
        $rel = null
    ) {
        if ($src && !$host) {
            $src=GEO_BASE.$src;
        }
        //geoDb($src,'src');
        $this->setAtt("src", $src);
        $this->setAtt("alt", $alt);
        $this->setHost($host);
        $this->init('img', null, $class, $id, $style, $atts);
        $this->setLink($href, $title, $target, $rel);
    }
   
    /**
     * Set Absolute Path (which is then used by PHP's getimagesize()
     *
     * @return void
     */
    private function setAbsolutePath()
    {
        if ($this->atts["src"]) {
            if (function_exists("apache_lookup_uri")) {
                $info=apache_lookup_uri($this->atts["src"]);
                $this->absolutePath=$info->filename;
            } else {
                $this->absolutePath=$_SERVER["DOCUMENT_ROOT"].geoIf($this->atts["src"][0]!="/", "/").$this->atts["src"];
            }
        }
    }
    
    /**
     * Set width and height attributes
     *
     * @return void
     */
    private function setImageSize()
    {
        if ($this->atts['src'] &&!$this->host) {
            $this->setAbsolutePath();
            $imageSize=getimagesize($this->absolutePath);
            
            if ($imageSize) {
                $this->setAtt("width", $imageSize[0]);
                $this->setAtt("height", $imageSize[1]);
            }
        }
    }
    
    /**
     * Set host for image src
     *
     * @param string $host host of image src (host is optional)
     *
     * @return void
     */
    public function setHost($host = null)
    {
        if ($host === true) {
            $host = "http://" . $_SERVER["HTTP_HOST"];
        } elseif ($host == null && defined("GEO_IMAGE_PATH")) {
            $host = GEO_IMAGE_PATH;
        }
        
        $this->host = $host;
    }
    
    /**
     *  Add a link to the image.
     *
     * @param string $href   href attribute of the link
     * @param string $title  title of the link
     * @param string $target target of the link
     * @param string $rel    rel of the link
     *
     * @return void
     */
    public function setLink($href, $title = null, $target = null, $rel = null)
    {
        if ($href && !$title) {
            $title = true;
        }
        
        $this->href   = $href;
        $this->title  = $title;
        $this->target = $target;
        $this->rel    = $rel;
    }
           
    /**
     * Returns html image tag
     *
     * @param string $src Path to image
     * @param string $alt Alternative text
     *
     * @return string HTML image tag
     */
    public function tag($src = null, $alt = null)
    {
        if ($alt) {
            $this->setAtt("att", $alt);
        }
        
        if ($src) {
            $this->setAtt("src", $src);
        }
        
        if ($this->host) {
            $this->setAtt("src", $this->host.$this->atts['src']);
        }
        
        $this->setImageSize();
        $tag = parent::baseTag();
       
        if ($this->href) {
            return geoLink(
                $this->href,
                $tag,
                null,
                $this->title,
                null,
                array("rel" => $this->rel),
                $this->target
            );
        }
        
        return $tag;
    }
}
