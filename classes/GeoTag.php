<?php
/**
 * Document for class GeoTag, a basic html tag object
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

class GeoTag
{
    private $tag;
    public $text;
    protected $atts;
    private $addons;
    private $isinline;
    
    /**
     * Constructor
     *
     * @param string        $tag   HTML tag
     * @param array||string $text  Content of tag(s). Use an array to create multiple instances of the same tag
     * @param string        $class Class
     * @param string        $id    Id
     * @param string        $style Style
     * @param string        $atts  Any other attribute
     */
    public function __construct($tag, $text = null, $class = null, $id = null, $style = null, $atts = null)
    {
        $this->init($tag, $text, $class, $id, $style, $atts);
    }
    
    /**
     * Set tag attribute
     *
     * @param string $att   Name of attribute
     * @param string $value Value of attribute
     *
     * @return void
     */
    public function setAtt($att, $value = null)
    {
        // Checking for the value insures that individually set atts do not override
        // atts which have already been set by $atts
        
        if (isset($value)) {
            $this->atts[$att] = $value;
        }
    }
    
    /**
     * Set tag content
     *
     * @param string $text conteht value
     *
     * @return void
     */
    public function setText($text)
    {
        if (isset($text)) {
            $this->text = $text;
        }
    }
    
    /**
     * Initialize tag content and attributes Set number of rows to span
         Also determines whether tag is inline, meaning it has no content or separate end tag.
     *
     * @param string       $tag   HTML tag
     * @param string       $text  Tag content
     * @param string       $class Class attribute
     * @param string       $id    Id attribute
     * @param string       $style Style attribute
     * @param string|array $atts  Any other attributes
     *
     * @return void
     */
    protected function init($tag, $text = null, $class = null, $id = null, $style = null, $atts = null)
    {   
        if(Geo::session("isForEmail","page")){
            
            $margin = $style;
            $style = $class;
            $class = null;
            
            if($id){
              $style="font-size:".$id.";".$style;
              $id=null;
            }
            
            if($margin || $margin===0){
              $style="margin:".$margin.";".$style;
            }
        }

        $inlines = array(
            'img',
            'link',
            'meta',
            'input',
            'base'
        );
        if (in_array($tag, $inlines)) {
            $this->isinline = true;
        }
        
        if ($atts) {
            //$atts = Geo::arr($atts);// atts should always be an array, so this is probably not neccesary
            foreach ($atts as $att => $value) {
                $this->setAtt($att, $value);
            }
        }
        $this->tag = $tag;
        $this->setText($text);
        $this->setAtt("class", $class);
        $this->setAtt("id", $id);
        $this->setAtt("style", $style);
    }
    
    /**
     * Creates tag for output
     *
     * @param bool $start Indicates that this tag is only the first part of an inline tag, plus the content.
     * @param bool $end   Indicates that this tag is only an end tag.
        Start and end indicators can be used when tags are out put directly to web page without being stored in buffer.
     *
     * @return void
     */
    public function baseTag($start = null, $end = null)
    {
        $tags = $attsline = '';
        if ($end) {
            return "</" . $this->tag . ">";
        }
        
        $this->text = Geo::arr($this->text);
        
        foreach ($this->text as $key => $value) {
            $attsarr = array();
            $tags .= "<" . $this->tag;
            $idline = '';
           
            if ($this->atts) {
                foreach ($this->atts as $key2 => $value2) {
                    if (isset($value2)) {
                        $attsarr[] = $key2 . '="' . Geo::ifArr($value2, $key).
                        geoIf($key2 == 'id' && $key, "_" . $key) . '"';
                    }
                }
            }
            
            if ($attsarr) {
                $attsline = implode(' ', $attsarr);
            }
            
            if ($attsline) {
                $tags .= " " . $attsline;
            }
            
            if ($this->isinline) {
                if (defined("GEO_IS_XHTML") && GEO_IS_XHTML) {
                    $tags .= ' /';
                }
                
                $tags .= ">";
            } else {
                $tags .= ">" . $value . geoIf(!$start, "</" . $this->tag . ">");
            }
            
        }
        
        return $tags;
    }
}
