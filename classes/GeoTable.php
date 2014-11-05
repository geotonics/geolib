<?php

/**
 * Document for class GeoTable
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
* Geo - Class to create html table tags
*
 * @category Html_Tag
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @version  Release: .1
 * @link     http://geotonics.com/#geolib
 * @since    Class available since Release .1
*/
class GeoTable extends GeoTag
{
    protected $rows = array();
    private $colAligns = array();
    private $rowAligns = array();
    private $colValigns = array();
    private $rowClasses = array();
    private $titles = array();
    private $rowValigns = array();
    private $isRows = array();
    
    /**
     * Class constructor
     *
     * @param array  $rows        table content
     * @param string $class       class of table
     * @param array  $rowClasses  classes for table rows
     * @param string $colClasses  classes for table columns
     * @param string $id          id of table
     * @param int    $cellspacing cellspacing of table
     * @param int    $cellpadding cellpadding of table
     * @param int    $border      html width of table border
     * @param int    $width       width of table
     * @param string $colValigns  valigns for table columns (each cell in the column will have this valign value)
     * @param string $rowValigns  valigns for table rows
     * @param string $colAligns   aligns for table columns (each cell in the column will have this align value)
     * @param string $rowAligns   aligns for table rows
     */
    public function __construct(
        $rows = null,
        $class = null,
        $rowClasses = null,
        $colClasses = null,
        $id = null,
        $cellspacing = 0,
        $cellpadding = 0,
        $border = 0,
        $width = null,
        $colValigns = null,
        $rowValigns = null,
        $colAligns = null,
        $rowAligns = null
    ) {
        $this->setAtt("width", $width);
        
        if ($cellspacing){
            $this->setAtt("cellspacing", $cellspacing);
        }
        
        $this->setAtt("cellpadding", $cellpadding);
        
        if ($border) {
            $this->setAtt("border", $border);
        }
        
        $this->setRowClasses($rowClasses);
        $this->setColClasses($colClasses);
        $this->colAligns=$colAligns;
        $this->rowAligns=$rowAligns;
        $this->colValigns=$colValigns;
        $this->rowValigns=$rowValigns;

        $this->init('table', $rows, $class, $id);
    }
    
    /**
     * Set table content
     *
     * @param array $rows 1 or 2 dimensional array for row content
     *
     * @return void
     */
    public function setText($rows)
    {
        if ($rows) {
            $rows = Geo::arr($rows);
            $this->rows = $rows;
        }
    }
    
    /**
     * Set table titles
     *
     * @param array $titles Set Alternative method for setting first row of content.
     *
     * @return void
     */
    public function setTitles($titles)
    {
        $this->titles = $titles;
    }
    
    /**
     * Set table column classes
     *
     * @param array $colClasses column classes
     *                          Each row in this array becomes the class foir each cell in the table row
     *
     * @return void
     */
    public function setColClasses($colClasses = null)
    {
        $this->colClasses = $colClasses;
    }
    
    /**
     * Set table row classes
     *
     * @param array $rowClasses row classes
     *
     * @return void
     */
    public function setRowClasses($rowClasses = null)
    {
        $this->rowClasses = $rowClasses;
    }
    
    /**
     * Set table cell classes
     *
     * @param array $cellClasses 2 d array of cell classes
     *
     * @return void
     */
    public function setCellClasses($cellClasses = null)
    {
        $this->cellClasses = $cellClasses;
    }
    
    /**
     * Set table row styles
     *
     * @param array $rowStyles Styles for each row
     *
     * @return void
     */
    public function setRowStyles($rowStyles = null)
    {
        $this->rowStyles = $rowStyles;
        
    }
    
    /**
     * Set table cell styles
     *
     * @param array $cellStyles 2 d array of cell styles
     *
     * @return void
     */
    public function setCellStyles($cellStyles = null)
    {
        $this->cellStyles = $cellStyles;
        
    }
    
    /**
     * Set flag to indicate content style
     *
     * @param array $isRows Flag to indicate content is html, not an array
     *
     * @return void
     */
    public function isRows($isRows = null)
    {
        $this->isRows = $isRows;
    }
    
    /**
     * Sort columns of one row
     *
     * @param array $row row to sort
     *
     * @return void
     */
    public function sortColumns($row)
    {
        foreach ($this->titles as $key => $title) {
            if (isset($row[$key])) {
                $row2[$key] = $row[$key];
            }
        }
        return $row2;
    }
    
    /**
     * Sort all columns. Columns are sorted by title keys.
           To use this feature, sort and add titles, then sortAllColumns
     *
     * @return void
     */
    public function sortAllColumns()
    {
        if ($this->rows) {
            foreach ($this->rows as $key => $row) {
                $this->rows[$key] = $this->sortColumns($row);
            }
            
        }
    }
    
    /**
     * Set align of one column
     *
     * @param int  $col      Column number
     * @param text $colalign align for each cell in column
     *
     * @return void
     */
    public function setColAlign($col = 0, $colalign = 'right')
    {
        $this->colAligns[$col] = $colalign;
    }
    
    /**
     * Set valign of one column
     *
     * @param int  $col       Column number
     * @param text $colvalign valign for each cell in column
     *
     * @return void
     */
    public function setColValign($col = 0, $colvalign = 'top')
    {
        $this->colValigns[$col] = $colvalign;
        foreach ($this->rows as $row) {
            $colnum = 0;
            foreach ($row as $cell) {
                if ($colnum++ == $col) {
                    $cell->setAtt("valign", $colvalign);
                }
                
                $row2[] = $cell;
            }
            $rows2[] = $row2;
            $row2    = '';
        }
        $this->rows = $rows2;
    }
    
    /**
     * Set class of one column
     *
     * @param int  $col      Column number
     * @param text $colclass class for each cell in column
     *
     * @return void
     */
    public function setColClass($col = 0, $colclass = 'colclass')
    {
        $this->colClasses[$col] = $colclass;
    }
    
    /**
     * Set align of one row
     *
     * @param int  $row      Row number
     * @param text $rowalign align for row
     *
     * @return void
     */
    public function setRowAlign($row = 0, $rowalign = 'center')
    {
        $this->rowAligns[$row] = $rowalign;
    }
    
    /**
     * Set valign of one row
     *
     * @param int  $row       Row number
     * @param text $rowvalign valign for each cell in row
     *
     * @return void
     */
    public function setRowValign($row = 0, $rowvalign = 'top')
    {
        $this->rowValigns[$row] = $rowvalign;
    }
    
    /**
     * Set class of one row
     *
     * @param int  $row      Row number
     * @param text $rowclass class for row
     *
     * @return void
     */
    public function setRowClass($row = 0, $rowclass = 'rowclass')
    {
        $this->rowClasses[$row] = $rowclass;
    }
    
    /**
     * Set style of one row
     *
     * @param int   $row      Row number
     * @param array $rowStyle style for each cell in row
     *
     * @return void
     */
    public function setRowStyle($row = 0, $rowStyle = 'rowStyle')
    {
        $this->rowStyles[$row] = $rowStyle;
    }
    
    /**
     * Print table tag
     *
     * @param array $rows       Content for table
     * @param array $altcontent Alernative content (Only used if table content is empty
     *
     * @return void
     */
    public function tag($rows = null, $altcontent = null)
    {
        $this->setText($rows);
        if ($this->titles && $this->rows) {
            array_unshift($this->rows, $this->titles);
        } elseif ($this->titles) {
            $this->rows = array(
                $this->titles
            );
        }
        if ($this->rows) {
            $rowNum = 0;
            foreach ($this->rows as $rowKey => $row) {
                $row     = Geo::arr($row);
                $cellnum = 0;
                
                foreach ($row as $cellKey => $cell) {
                    if (!is_object($cell)) {
                        $cell = geoCell($cell);
                    }
                    
                    if (isset($this->colClasses[$cellnum]) && !($this->titles && !$rowNum)) {
                        $cell->setAtt("class", $this->colClasses[$cellnum]);
                    }
                    
                    if (isset($this->colAligns[$cellnum])) {
                        $cell->setAtt("align", $this->colAligns[$cellnum]);
                    }
                    
                    if (isset($this->cellClasses[$rowKey][$cellnum]) && is_array($this->cellClasses[$rowKey])) {
                        $cell->setAtt("class", $this->cellClasses[$rowKey][$cellnum]);
                    }
                    
                    if (isset($this->cellStyles[$rowKey][$cellnum])) {
                        if (is_array($this->cellStyles[$rowKey])) {
                            $cell->setAtt("style", $this->cellStyles[$rowKey][$cellnum]);
                        } else {
                            $cell->setAtt("style", $this->cellStyles[$rowKey]);
                        }
                    }
                    
                    $row2[$cellKey] = $cell;
                    $cellnum++;
                }
                $rows2[$rowKey] = $row2;
                $row2           = '';
                $rowNum++;
            }
            $this->rows = $rows2;
        }
        
        $numRows = 0;
        $tablecontent = '';
        if ($this->rows) {
            foreach ($this->rows as $key => $row) {
                if (!$this->isRows) {
                    $tablecontent .= "<tr";
                    
                    if (isset($this->rowAligns[$numRows])) {
                        $tablecontent .= " align=\"" . $this->rowAligns[$numRows] . "\"";
                    }
                    
                    if (isset($this->rowValigns[$numRows])) {
                        $tablecontent .= " valign=\"" . $this->rowValigns[$numRows] . "\"";
                    }
                    
                    if (isset($this->rowClasses[$numRows])) {
                        if (!is_array($this->rowClasses[$numRows])) {
                            $tablecontent .= " class=\"" . $this->rowClasses[$numRows] . "\"";
                        }
                    }
                    
                    if (isset($this->rowStyles[$numRows])) {
                        if (!is_array($this->rowStyles[$numRows])) {
                            $tablecontent .= " style=\"" . $this->rowStyles[$numRows] . "\"";
                        }
                    }
                    
                    if (!is_int($key)) {
                        // use array key as row id
                        $key2 = str_replace(" ", "_", $key); // spaces are invalid in id
                        
                        if (is_numeric($key2[0])) {
                            // id which begin with integers are invalid
                            $key2 = "id_" . $key2;
                        }
                        
                        $tablecontent .= " id=\"" . $key2 . "\"";
                    }
                    
                    $numRows++;
                    $tablecontent .= ">";
                }
                
                foreach ($row as $cell) {
                    $tablecontent .= $cell->tag();
                }
                
                if (!$this->isRows) {
                    $tablecontent .= "</tr>";
                }
            }
        }
        if ($tablecontent) {
            if ($this->isRows) {
                return $tablecontent;
            } else {
                $this->text = Geo::arr($tablecontent);
            }
            return parent::baseTag($rows);
        } else {
            return $altcontent;
        }
    }
}
