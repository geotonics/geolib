<?php

/**
 * Document for class GeoSelect
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
* Geo - Class to create HTML select tags
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class GeoSelect extends GeoTag
{
    // takes array of options in the form array(id, name);
    private $selected;
    private $size;
    
    
    
    /**
     * Construct html select tag
     *
     * @param string            $name     Name of select tag
     * @param array             $options  1 or 2 dimensional array of options
     *     If 1 dimensional each row is  $name=>$value
     *     If 2 dimensional each array is 0=>name 1=>value 2=>class
     * @param string            $selected name of selected option
     * @param string            $id       id of select tag
     * @param array|string|bool $atts     attributes of select tag
     *     If array, is atts of select tag.
     *     If string, is a single default att
     *     If PHP true, att is onchange=>javascript to submit form
     * @param string            $size     size att of select tag
     * @param string            $class    class of select tag
     * @param string            $style    style of select tag
     */
    public function __construct(
        $name = null,
        $options = null,
        $selected = null,
        $id = null,
        $atts = null,
        $size = null,
        $class = null,
        $style = null
    ) {
        
        // allows simpler key->value options array, but no additional option atts
        // for other options, use 2d array
        //GeoDebug::db($options, 'options');
        
        if ($options && is_array($options) && !geoIsMultiArr($options)) {
            foreach ($options as $key => $value) {
                if (is_object($value)) {
                    if ($value->name) {
                        $value = $value->name;
                    } elseif (isset($value->machinename)) {
                        $value = $value->machinename;
                    } else {
                        continue;
                    }
                }
                $options2[] = array(
                    "id" => $key,
                    "name" => $value
                );
            }
            $options = $options2;
        }
        if ($atts === true) {
            $atts = "this.form.submit()";
        }
        
        if ($atts && !is_array($atts)) {
            $newatts['onchange'] = $atts;
            $atts                = $newatts;
        }

        if (!isset($id) && strpos($name, "[") === false) {
            $id = $name;
        }
        
        $atts['name'] = $name;
        
        if ($size) {
            $atts['size'] = $size;
        }
        
        $this->init("select", $options, $class, $id, $style, $atts);
        //GeoDebug::db($this, 'this');
        $this->setSelected($selected);
    }
    
    /**
     * Set selected option
     *
     * @param string $selected name of selected option
     *
     * @return void
     */
    public function setSelected($selected)
    {
        if (is_array($selected)) {
            array_unshift($this->text, $selected);
            $this->selected = $selected['id'];
        } elseif (isset($selected)) {
            $this->selected = $selected;
        } elseif (isset($this->atts['name']) && isset($_POST[$this->atts['name']])) {
            $this->selected = $_POST[$this->atts['name']];
        }
    }
    
    /**
     * Output HTML select tag
     *
     * @return html select tag
     */
    public function tag()
    {
        if ($this->text) {
            $result      = '';
            $hasSelected = null;
            foreach ($this->text as $optNum => $option) {
                //GeoDebug::db($option, $optNum);
                if (is_array($option)) {
                    $option = array_values($option);
                } else {
                    $option = array(
                        $option,
                        $option
                    );
                }
                
                $atts          = array();
                $atts['value'] = $option[0];
               
                if (isset($this->selected) && ((string)$option[0] == (string)$this->selected) && !$hasSelected) {
                    $atts["selected"] = "selected";
                    $hasSelected      = true;
                   
                }

                if (isset($option[2])) {
                    $atts["class"] = $option[2];
                }

                if (isset($option[1])) {
                    // disallow empty options
                    $result .= geoTag("option", $option[1], null, null, null, $atts);
                }
            }
            $this->text = $result;
        }

        return parent::baseTag();
    }
}
