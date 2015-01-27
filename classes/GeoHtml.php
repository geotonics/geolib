<?php
/**
 * Document for class GeoHtml
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
* Geo - Class to create html html tags
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class GeoHtml extends GeoTag
{
    /**
     * Constructor for GeoHtml Class
     *
     * @param string|object $body  html body tag or GeoBody object
     * @param string|object $head  A GeoHead object, usually from geoHead(). A string for the title can be used
     *                                 if no other head attributes are required.
     * @param bool          $start If true, tag is the intial tag and the content. Leave out end tag.
     */
    public function __construct($body = null, $head = null, $start = null)
    {
        if (isset($head)) {
            if (!is_object($head)) {
                $head = geoHead($head);
            }
            
        } else {
            $head = geoHead('');
        }
        
        if (!is_object($body)) {
            $body = geoBody($body);
        }
        
        $this->start = $start;
        
        $body->text .= $head->scripts();
        header("Content-Type: text/html; charset=utf-8'");
        $this->setDoctypeTag();
        parent::__construct("html", $head->tag() . $body->tag(null, $start));
    }
    
    /**
     * Set the Doctype, set a flag if Doctype is XHTML
     *
     * @param string $doctype String representing the doctype to be used
     *
     * @return void
     */
    public static function setDoctype($doctype = "HTML 5")
    {
        define("GEO_DOCTYPE", $doctype);
        define("GEO_IS_XHTML", geoIf($doctype == "XHTML 1.0 Strict", true, false));
    }
    
    /**
     * Create the doctype tag based on the DOCTYPE set by setDocType()
     * @return void
     */
    private function setDoctypeTag()
    {
        switch (GEO_DOCTYPE) {
            case "XHTML 1.0 Strict":
                $this->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"'."\n".
                    '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
                $this->setatt('xmlns', "http://www.w3.org/1999/xhtml");
                $this->setatt('xml:lang', "en");
                $this->setatt('lang', "en");
                break;
            case "HTML 4.01 Transitional":
                $this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"'."\n".
                    '"http://www.w3.org/TR/html4/loose.dtd">';
                break;
            case "HTML 4.01 Frameset":
                $this->doctype .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" 
                    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
                break;
            case "HTML 4.01 strict":
                $this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"' . "\n".
                    '"http://www.w3.org/TR/html4/strict.dtd">';
                break;
            case "HTML 5":
            default:
                $this->doctype = '<!DOCTYPE html>';
                break;
        }
        
    }
    
    /**
     * Creates HTML page
     *
     * @param bool $tidyTag Turn tidy on, so that source code is printed with pretty indentation.
     *
     * @return Doctype and HTML tags
     */
    public function tag($tidyTag = null)
    { 
        if (GeoDebug::isOn()) {
            
            $this->text = str_replace("</body>", GeoDebug::vars()."</body>", $this->text);
            
            if (!$tidyTag && $tidyTag !== false) {
                $this->text = "\n".Geo::tidy($this->text)."\n";
            }
            
        } elseif ($tidyTag) {
            $this->text = Geo::tidy($this->text);
        } else {
            $this->text = str_replace(
                array("\n", "\r"),
                " ",
                $this->text
            );
        }
        
        GeoDebug::reset();
        return $this->doctype . "\n" . parent::baseTag($this->start);
    }
}
