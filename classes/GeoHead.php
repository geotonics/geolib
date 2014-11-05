<?php
/**
 * Document for class GeoHead
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
* Geo - Class to create html head tags
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class GeoHead extends GeoTag
{
    private $title;
    private $stylesheets;
    private $scripts;
    private $headScripts;
    private $styles;
    private $media;
    private $addlTags;
    private $base;
    private $tags;
    private $addHtml;
    
    /**
     * Constructor
     *
     * @param string        $title       Document title
     * @param array|string  $stylesheets Paths to document stylesheets. Can be 1 stylesheet or an array
     * @param string|string $scripts     Paths to document scripts. Can be either 1 script or an array
     * @param string        $styles      Styles for style tag in head
     * @param array         $metas       Content of metatags
     * @param string        $addlTags    Any additional tags for head
     *                                   addlTags can be multi or non multi. Examples:
     *                                      array:$tags["script"][$script]=array("type"=>'text/script');
     *                                      $addlTags['link'][]=array("rel"=>'goback');
     *                                      $addlTags['link']=array("rel"=>'goback');
     * @param string        $media       Media attribute
     * @param string        $addHtml     Any additional html for head
     */
    public function __construct(
        $title = null,
        $stylesheets = null,
        $scripts = null,
        $styles = null,
        $metas = null,
        $addlTags = null,
        $media = null,
        $addHtml = null
    ) {
                
        $this->setScripts($scripts, 'scripts');
        $this->setStylesheets($stylesheets);
        $this->setStyles($styles);
        $this->setTitle($title);
        $this->media = $media;
        $this->setMetas(
            array(
            null,
            "text/html; charset=utf-8",
            "Content-Type",
            null
            )
        );
        $this->setMetas($metas);
        
        if (isset($addlTags)) {
            $this->addTags($addlTags);
        }
        
        if (isset($addHtml)) {
            $this->addHtml($addHtml);
        }
        
        //GeoDebug::db($this->tags, 'thistags');
        parent::__construct("head");
    }
    
    /**
     * Set content of style tags inside head
     *
     * @param array $styles Each row gets its own style tag
     *
     * @return void
     */
    public function setStyles($styles)
    {
        $this->styles = $styles;
    }
    
    /**
     * Set content of title tag
     *
     * @param text $title document title
     *
     * @return void
     */
    public function setTitle($title)
    {
        if (!$title) {
            $title = $_SERVER["PHP_SELF"];
        }
        
        $this->title = $title;
    }
    
    /**
     * Set document metatags
     *
     * @param array $metas   Content of metatags.
     * @param bool  $replace If true, replace metas. Default is to add $metas to existing metas.
     *
     * @return void
     */
    public function setMetas($metas, $replace = null)
    {
        if ($metas) {
            $metas = geoMultiArr($metas);
            foreach ($metas as $key => $meta) {
                //initialize missing array values
                for ($i = 0; $i < 4; $i++) {
                    if (!isset($meta[$i])) {
                        $meta[$i] = null;
                    }
                }
                
                if ($meta[0]) {
                    $newMetas[$meta[0]] = $meta;
                } else {
                    $newMetas[] = $meta;
                }
            }
            $this->setTags($newMetas, "metas", $replace);
        }
    }
    
    /**
     * Set links to stylesheets
     *
     * @param array $stylesheets hrefs of css files
     * @param bool  $replace     If true, replace stylesheets. Default is to add $stylesheets to existing metas.
     *
     * @return void
     */
    public function setStylesheets($stylesheets, $replace = null)
    {
        $this->setTags($stylesheets, 'stylesheets', $replace);
    }
    
    /**
     * Set scripts
     *
     * @param array $scripts   hrefs of script files
     * @param bool  $replace   If true, replace script hrefs. Default is to add $scripts to existing scripts.
     * @param bool  $isForHead If true, script tags will be added to the head.
     *                         Default is to add script tags to the end of the body
     *
     * @return void
     */
    public function setScripts($scripts, $replace = null, $isForHead = null)
    {
        if ($isForHead) {
            $this->setTags($scripts, 'headScripts', $replace);
        } else {
            $this->setTags($scripts, 'scripts', $replace);
        }
    }
    
    /**
     * Set head tags
     *
     * @param array  $tags    Content of head tags.
     * @param string $name    Tag to put content into.
     * @param bool   $replace If true, replace $name tags. Default is to add $name tags to existing $name tags.
     *
     * @return void
     */
    private function setTags($tags, $name = null, $replace = null)
    {
        if ($tags) {
            $tags = Geo::arr($tags);
            if ($replace || !isset($this->$name)) {
                $this->$name = $tags;
            } else {
                $this->$name = array_merge($this->$name, $tags);
            }
        }
    }
    
    /**
     * Set Base
     *
     * @param array $base Set document base
     *
     * @return void
     */
    public function setBase($base)
    {
        $this->base = $base;
    }
    
    /**
     * Get scripts. Sets scripts for the head or the body via geohtmlObj
     *
     * @param bool $getHeadScripts Indicates whether to get scripts indended for the head or the body. Default is body.
     *
     * @return string $scripts Script tags
     */
    public function scripts($getHeadScripts = null)
    {
        if ($getHeadScripts) {
            $scriptArr = $this->headScripts;
        } else {
            $scriptArr = $this->scripts;
        }
        
        if ($scriptArr) {
            $scripts='';
            
            foreach ($scriptArr as $URL) {
                $scripts .= geoScript($URL);
            }
            
            return $scripts;
        }
    }
    
    /**
     * Add tags to the head
     *
     * @param array|string $addlTags Additional tags for the head.
     *                               Can be a 3 D array in the form of $array($tagname=>$tagContentArray)
     *                               Can be an 3 D array in the form of $array($tagname=>$tagContentString)
     *                               Can be a string. String is assumed to be the content of a script tag
     *
     * @return string $scripts Script tags
     */
    public function addTags($addlTags)
    {
        if ($addlTags) {
            if (is_array($addlTags)) {
                foreach ($addlTags as $tagName => $tags) {
                    if (geoIsMultiArr($tags)) {
                        foreach ($tags as $text => $atts) {
                            $this->tags[$tagName][$text] = $atts;
                        }
                    } else {
                        $this->tags[$tagName][] = $tags;
                    }
                }
            } else {
                // assume its javascript for a script tag
                $this->tags['script'][$addlTags] = array(
                    "type" => "text/javascript"
                );
            }
        }
    }
    
    /**
     * Add Html to head
     *
     * @param string $html Html to be added to head
     *
     * @return void
     */
    public function addHtml($html)
    {
        $this->addHtml = $html;
    }

    /**
     * HTML head tag
     *
     * @return string head tag
     */
    public function tag()
    {
        if ($this->title) {
            $this->text = geoTag('title', $this->title);
        }
        
        if ($this->metas) {
            foreach ($this->metas as $meta) {
                $this->tags['meta'][] = array(
                    "name" => $meta[0],
                    "content" => $meta[1],
                    "http-equiv" => $meta[2],
                    "scheme" => $meta[3]
                );
            }
        }
        
        if ($this->stylesheets) {
            foreach ($this->stylesheets as $cssUrl) {
                $this->tags['link'][] = array(
                    'rel' => 'stylesheet',
                    'type' => 'text/css',
                    'href' => $cssUrl
                );
            }
        }
        
        if ($this->styles) {
            $this->tags['style'][$this->styles] = array(
                'type' => 'text/css',
                "media" => $this->media
            );
        }
        
        $this->text .= $this->scripts(true);
        
        if ($this->base) {
            $this->tags['base'][] = array(
                'target' => $this->base
            );
        }
        
        $this->tags["link"][] = array(
            "rel" => "SHORTCUT ICON",
            "href" => "/images/favicon.ico"
        );
        
        foreach ($this->tags as $name => $arr) {
            foreach ($arr as $text => $atts) {
                $this->text .= geoTag($name, $text, null, null, null, $atts);
            }
        }
        
        if ($this->addHtml) {
            $this->text .= $this->addHtml;
        }
        
        return parent::baseTag();
    }
}
