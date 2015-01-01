<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (ↄ) 2013-2015 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Library\Tool;

use \ArrayIterator;
use \InvalidArgumentException;

/*
TODO
$this->line_index
$this->cell_index
$this->column_index

PAD by columns

*/

/**
 * Table helper tool
 *
 * ## Presentation
 *
 * This class helps to build and work with table in an HTML style, meaning that the table
 * may have a caption, and three parts separated in a header, a footer and a body composed
 * of lines separated in cells. The class does NOT build any HTML string but organizes and
 * completes each table part to have a cleaned and ready-to-work-with array representation
 * of the table.
 *
 * A full text schema like a Mysql query result is embedded using method `render()`.
 *
 * ## Construction of the table
 *
 * The table built in this class can be schematized like below ; it is considered as a set
 * of cells stored in lines that builds columns.
 *
 *     +------------+------------+
 *     | table      | headers    | // "thead" : can be more than 1 line
 *     +------------+------------+
 *     | table      | line 1     | // "tbody"
 *     | table      | line 2     |
 *     +------------+------------+
 *     | table      | footers    | // "tfoot" : can be more than 1 line
 *     +------------+------------+
 *
 * This schema is render as a matrix like, concerning only the body:
 *
 *            | col A   | col B   |
 *            ---------------------
 *     line 1 | cell A1 | cell B1 |
 *     line 2 | cell A2 | cell B2 |
 *
 * Lines, columns and cells are 1 based arrays: first item has key "1" when you want 
 * to get or set a position.
 *
 * ## Usage
 *
 * For convenience, the best practice is to use an alias:
 *
 *     use Library\Tool\Table as TableTool;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class Table
{

    /**
     * Using this flag as `$pad_flag`, last cell is pad as an HTML `colspan` for each line if necessary
     */
    const PAD_BY_SPAN           = 1;

    /**
     * Using this flag as `$pad_flag`, each line is completed by empty cells if necessary
     */
    const PAD_BY_EMPTY_CELLS    = 2;

    /**
     * Using this flag, the `getTableIterator` method returns an iterator on table lines
     */
    const ITERATE_ON_LINES      = 1;

    /**
     * Using this flag, the `getTableIterator` method returns an iterator on table columns
     */
    const ITERATE_ON_COLUMNS    = 2;

    /**
     * @var string Mask used as the default footer ; parsed with `columns number , lines number , cell size`
     */
    public static $default_foot_mask = 'Table of %d columns and %d lines - cell length of %d chars.';

    /**
     * Table title
     * @var string
     */
    protected $title = '';

    /**
     * Table `thead` lines
     * @var array
     */
    protected $thead = array();

    /**
     * Table `tbody` lines
     * @var array
     */
    protected $tbody = array();

    /**
     * Table `tfoot` lines
     * @var array
     */
    protected $tfoot = array();

    /**
     * Table number of columns
     * @var int
     */
    protected $column_size = 0;

    /**
     * Table number of `tbody` lines
     * @var int
     */
    protected $line_size = 0;

    /**
     * Table max length of cells
     * @var int
     */
    protected $cell_size = 0;

    /**
     * Flag used for last cell of a line padding (in case the line doesn't have enough cells)
     *
     * This must be one of the class `PAD_` constants.
     *
     * @var int
     */
    protected $pad_flag;

    /**
     * Internal reminder of a table parts structure
     * @var array
     */
    protected static $_table_parts = array( 0=>'thead', 1=>'tbody', 2=>'tfoot' );

    /**
     * Table construction
     *
     * @param array $body The array of body lines
     * @param array $header The array of headers lines
     * @param array $footer The array of footers lines
     * @param string $title The table title
     * @param int $pad_flag The flag to use for cell padding, must be one of the class `PAD_` constants
     * @see self::setPadFlag()
     * @see self::setTitle()
     * @see self::setBody()
     * @see self::setHeader()
     * @see self::setFooter()
     */
    public function __construct(
        array $body = array(), array $header = array(), array $footer = array(),
        $title = null, $pad_flag = self::PAD_BY_EMPTY_CELLS
    ) {
        $this->setPadFlag($pad_flag);
        $this->_resetSizes();
        if (!empty($body)) {
            $this->setBody($body);
        }
        if (!empty($header)) {
            $this->setHeader($header);
        }
        if (!empty($footer)) {
            $this->setFooter($footer);
        }
        if (!empty($title)) {
            $this->setTitle($title);
        }
    }

    /**
     * Rendering of the table
     *
     * @return string The result of `$this->render()`
     * @see self::render()
     */
    public function __toString()
    {
        return $this->render();
    }

// --------------------
// Whole table Setters / Getters
// --------------------

    /**
     * Get the full table array
     *
     * @return array
     */
    public function getTable()
    {
        $this->_resetSizes();
        $this->_repadAllLines();
        return array(
            'title'=>$this->getTitle(),
            'head'=>$this->getHeader(),
            'body'=>$this->getBody(),
            'foot'=>$this->getFooter(),
        );
    }

    /**
     * Get the full table or a part of the table as an `ArrayIterator` object
     *
     * With a `null` first parameter (default is `body`), the iterator will be constructed
     * on all the table lines or columns, first the headers one, then the body and finally
     * the footers one (without distinction).
     *
     * @param   string  $part           One of the parts of the table array built by method `getTable()`
     * @param   int     $iterator_flag  The flag to use to build the iterator, must be one of the class
     *                                  `ITERATE_` constants
     * @return  object An `ArrayIterator` instance
     * @throws  \InvalidArgumentException if the part doesn't exist in the table
     * @see     \ArrayIterator
     */
    public function getTableIterator($part = 'body', $iterator_flag = self::ITERATE_ON_LINES)
    {
        $this->_repadAllLines();
        $table = $this->getTable();
        unset($table['title']);
        if (!empty($part)) {
            if (isset($table[$part])) {
                if ($iterator_flag & self::ITERATE_ON_COLUMNS) {
                    $columns = array();
                    foreach ($table[$part] as $i=>$line) {
                        foreach ($line as $j=>$cell) {
                            if (!isset($columns[$j])) {
                                $columns[$j] = array();
                            }
                            $columns[$j][$i] = $cell;
                        }
                    }
                    return new ArrayIterator($columns);
                } else {
                    return new ArrayIterator($table[$part]);
                }
            } else {
                throw new InvalidArgumentException(
                    sprintf('Unknown table part "%s"!', $part)
                );
            }
        } else {
            $lines = array();
            foreach (self::$_table_parts as $_part) {
                if (isset($this->{$_part}) && !empty($this->{$_part})) {
                    foreach ($this->{$_part} as $line) {
                        $lines[] = $line;
                    }
                }
            }
            if ($iterator_flag & self::ITERATE_ON_COLUMNS) {
                $columns = array();
                foreach ($lines as $i=>$line) {
                    foreach ($line as $j=>$cell) {
                        if (!isset($columns[$j])) {
                            $columns[$j] = array();
                        }
                        $columns[$j][$i] = $cell;
                    }
                }
                return new ArrayIterator($columns);
            } else {
                return new ArrayIterator($lines);
            }
        }
    }

    /**
     * Get a line of the table body
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @return array|null
     */
    public function getLine($line_index = null)
    {
        return $this->getBodyLine($line_index);
    }

    /**
     * Add a new line in the table body
     *
     * @param array|string $contents The content of the line
     * @param mixed $default The default value for empty cells
     * @return self
     */
    public function addLine($contents = null, $default = null)
    {
        $this->addBodyLine(is_array($contents) ? $contents : array($contents), null, $default);
        return $this;
    }

    /**
     * Get a column of the table body
     *
     * @param int $column_index The index of the column to get, if `null`, the last column is returned
     * @return array|null
     */
    public function getColumn($column_index = null)
    {
        return $this->getBodyColumn($column_index);
    }

    /**
     * Add a new column in the table body
     *
     * @param array|string $body The array of body lines
     * @param mixed $default The default value for empty cells, if `null`, the class will use its `pad_flag`
     * @param array $headers The array of headers lines
     * @param array $footers The array of footers lines
     * @return self
     */
    public function addColumn($body = null, $default = null, $headers = null, $footers = null)
    {
        $this->addBodyColumn(is_array($body) ? $body : array($body), null, $default);
        if (!empty($headers)) {
            $this->setHeaderColumn(is_array($headers) ? $headers : array($headers), $this->getColumnSize());
        }
        if (!empty($foters)) {
            $this->setFooterColumn(is_array($footers) ? $footers : array($footers), $this->getColumnSize());
        }
        return $this;
    }

    /**
     * Get a cell of the table body
     *
     * @param int $line_index The index of the line to get, if `null`, last line is chosen
     * @param int $cell_index The index of the cell to get in the line, if `null`, last cell is returned
     * @return string|null
     */
    public function getCell($line_index = null, $cell_index = null)
    {
        return $this->getBodyCell($line_index, $cell_index);
    }

    /**
     * Add a new cell in the current line of the table body
     *
     * @param string $cell The content of the cell
     * @return self
     */
    public function addCell($cell = null)
    {
        $this->addBodyCell($cell);
        return $this;
    }

// --------------------
// Setters / Getters
// --------------------

    /**
     * Set the table flag used for cell padding
     *
     * @param int $flag The flag to use for cell padding, must be one of the class `PAD_` constants
     * @return self
     */
    public function setPadFlag($flag)
    {
        $this->pad_flag = $flag;
        $this->_repadAllLines();
        return $this;
    }

    /**
     * Get the table pad flag
     *
     * @return int
     */
    public function getPadFlag()
    {
        return $this->pad_flag;
    }

    /**
     * Set the table title
     *
     * @param string $title The table title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the table title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the table headers lines
     *
     * The parameter may be an array of array where each item is a line, or a simple
     * array that will be considered as 1 line.
     *
     * @param array $contents The array of headers lines
     * @return self 
     * @see self::_setPart()
     */
    public function setHeader(array $contents)
    {
        $this->_setPart($contents, 'thead');
        return $this;
    }

    /**
     * Set a table header line
     *
     * @param array $contents The content of the line
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the end of the headers
     * @param mixed $default The default value for empty cells
     * @return self 
     * @see self::_setPartLine()
     */
    public function setHeaderLine(array $contents, $line_index = null, $default = null)
    {
        $this->_setPartLine($contents, $line_index, $default, 'thead');
        return $this;
    }

    /**
     * Add a new table header line
     *
     * @param array $contents The content of the line
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the end of the headers
     * @param mixed $default The default value for empty cells
     * @return self 
     * @see self::_setPartLine()
     */
    public function addHeaderLine(array $contents, $line_index = null, $default = null)
    {
        $this->_setPartLine($contents, $line_index, $default, 'thead', 'insert');
        return $this;
    }

    /**
     * Set a column in the table header
     *
     * @param array $contents The content of the column
     * @param int $column_index The index of the column to set, if `null`, the column will be added at
     *              the end of each line
     * @param mixed $default The default value for empty cells, if `null`, the class will use its `pad_flag`
     * @return self 
     * @see self::_setPartColumn()
     */
    public function setHeaderColumn(array $contents = array(), $column_index = null, $default = null)
    {
        $this->_setPartColumn($contents, $column_index, $default, 'thead');
        return $this;
    }

    /**
     * Add a new column in the table header
     *
     * @param array $contents The content of the column
     * @param int $column_index The index of the column to set, if `null`, the column will be added at
     *              the end of each line
     * @param mixed $default The default value for empty cells, if `null`, the class will use its `pad_flag`
     * @return self 
     * @see self::_setPartColumn()
     */
    public function addHeaderColumn(array $contents = array(), $column_index = null, $default = null)
    {
        $this->_setPartColumn($contents, $column_index, $default, 'thead', 'insert');
        return $this;
    }

    /**
     * Set a table header cell
     *
     * @param array $content The content of the cell
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the headers'last line
     * @param int $cell_index The index of the cell to set in the line, if `null`, the cell will 
     *              be added at the end of the line
     * @return self 
     * @see self::_setPartCell()
     */
    public function setHeaderCell($content, $line_index = null, $cell_index = null)
    {
        $this->_setPartCell($content, $line_index, $cell_index, 'thead');
        return $this;
    }

    /**
     * Add a new table header cell
     *
     * @param array $content The content of the cell
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the headers'last line
     * @param int $cell_index The index of the cell to set in the line, if `null`, the cell will 
     *              be added at the end of the line
     * @return self 
     * @see self::_setPartCell()
     */
    public function addHeaderCell($content, $line_index = null, $cell_index = null)
    {
        $this->_setPartCell($content, $line_index, $cell_index, 'thead', 'insert');
        return $this;
    }

    /**
     * Get the table headers lines
     *
     * @return array The lines array
     * @see self::_getPart()
     */
    public function getHeader()
    {
        return $this->_getPart('thead');
    }

    /**
     * Get a table headers line
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @return array The corresponding line
     * @see self::_getPartLine()
     */
    public function getHeaderLine($line_index = null)
    {
        return $this->_getPartLine($line_index, 'thead');
    }

    /**
     * Get a table headers column
     *
     * @param int $column_index The index of the column to get, if `null`, the last line is returned
     * @return array The corresponding column
     * @see self::_getPartColumn()
     */
    public function getHeaderColumn($column_index = null)
    {
        return $this->_getPartColumn($column_index, 'thead');
    }

    /**
     * Get a table headers cell
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @param int $cell_index The index of the cell to get in the line, if `null`, the last cell is returned
     * @return array The corresponding cell
     * @see self::_getPartCell()
     */
    public function getHeaderCell($line_index = null, $cell_index = null)
    {
        return $this->_getPartCell($line_index, $cell_index, 'thead');
    }

    /**
     * Set the table body lines
     *
     * The parameter may be an array of array where each item is a line, or a simple
     * array that will be considered as 1 line.
     *
     * @param array $contents The array of body lines
     * @return self 
     * @see self::_setPart()
     */
    public function setBody(array $contents)
    {
        $this->_setPart($contents, 'tbody');
        return $this;
    }

    /**
     * Set a table body line
     *
     * @param array $contents The content of the line
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the end of the body
     * @return self 
     * @see self::_setPartLine()
     */
    public function setBodyLine(array $contents, $line_index = null)
    {
        $this->_setPartLine($contents, $line_index, 'tbody');
        return $this;
    }

    /**
     * Add a new table body line
     *
     * @param array $contents The content of the line
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the end of the body
     * @return self 
     * @see self::_setPartLine()
     */
    public function addBodyLine(array $contents, $line_index = null)
    {
        $this->_setPartLine($contents, $line_index, 'tbody', 'insert');
        return $this;
    }

    /**
     * Set a column in the table body
     *
     * @param array $contents The content of the column
     * @param int $column_index The index of the column to set, if `null`, the column will be added at
     *              the end of each line
     * @param mixed $default The default value for empty cells, if `null`, the class will use its `pad_flag`
     * @return self 
     * @see self::_setPartColumn()
     */
    public function setBodyColumn(array $contents = array(), $column_index = null, $default = null)
    {
        $this->_setPartColumn($contents, $column_index, $default, 'tbody');
        return $this;
    }

    /**
     * Add a new column in the table body
     *
     * @param array $contents The content of the column
     * @param int $column_index The index of the column to set, if `null`, the column will be added at
     *              the end of each line
     * @param mixed $default The default value for empty cells, if `null`, the class will use its `pad_flag`
     * @return self 
     * @see self::_setPartColumn()
     */
    public function addBodyColumn(array $contents = array(), $column_index = null, $default = null)
    {
        $this->_setPartColumn($contents, $column_index, $default, 'tbody', 'insert');
        return $this;
    }

    /**
     * Set a table body cell
     *
     * @param array $content The content of the cell
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the body's last line
     * @param int $cell_index The index of the cell to set in the line, if `null`, the cell will 
     *              be added at the end of the line
     * @return self 
     * @see self::_setPartCell()
     */
    public function setBodyCell($content, $line_index = null, $cell_index = null)
    {
        $this->_setPartCell($content, $line_index, $cell_index, 'tbody');
        return $this;
    }

    /**
     * Add a new table body cell
     *
     * @param array $content The content of the cell
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the body's last line
     * @param int $cell_index The index of the cell to set in the line, if `null`, the cell will 
     *              be added at the end of the line
     * @return self 
     * @see self::_setPartCell()
     */
    public function addBodyCell($content, $line_index = null, $cell_index = null)
    {
        $this->_setPartCell($content, $line_index, $cell_index, 'tbody', 'insert');
        return $this;
    }

    /**
     * Get the table body lines
     *
     * @return array The lines array
     * @see self::_getPart()
     */
    public function getBody()
    {
        return $this->_getPart('tbody');
    }

    /**
     * Get a table body line
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @return array The corresponding line
     * @see self::_getPartLine()
     */
    public function getBodyLine($line_index = null)
    {
        return $this->_getPartLine($line_index, 'tbody');
    }

    /**
     * Get a column of the table body
     *
     * @param int $column_index The index of the column to get, if `null`, the last column is returned
     * @return array|null
     * @see self::_getPartColumn()
     */
    public function getBodyColumn($column_index = null)
    {
        return $this->_getPartColumn($column_index, 'tbody');
    }

    /**
     * Get a table body cell
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @param int $cell_index The index of the cell to get in the line, if `null`, the last cell is returned
     * @return array The corresponding cell
     * @see self::_getPartCell()
     */
    public function getBodyCell($line_index = null, $cell_index = null)
    {
        return $this->_getPartCell($line_index, $cell_index, 'tbody');
    }

    /**
     * Set the table footer lines
     *
     * The parameter may be an array of array where each item is a line, or a simple
     * array that will be considered as 1 line.
     *
     * @param array $contents The array of footer lines
     * @return self 
     * @see self::_setPart()
     */
    public function setFooter(array $contents)
    {
        $this->_setPart($contents, 'tfoot');
        return $this;
    }

    /**
     * Set a table footer line
     *
     * @param array $contents The content of the line
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the end of the footers
     * @return self 
     * @see self::_setPartLine()
     */
    public function setFooterLine(array $contents, $line_index = null)
    {
        $this->_setPartLine($contents, $line_index, 'tfoot');
        return $this;
    }

    /**
     * Add a new table footer line
     *
     * @param array $contents The content of the line
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the end of the footers
     * @return self 
     * @see self::_setPartLine()
     */
    public function addFooterLine(array $contents, $line_index = null)
    {
        $this->_setPartLine($contents, $line_index, 'tfoot', 'insert');
        return $this;
    }

    /**
     * Set a column in the table footers
     *
     * @param array $contents The content of the column
     * @param int $column_index The index of the column to set, if `null`, the column will be added at
     *              the end of each line
     * @param mixed $default The default value for empty cells, if `null`, the class will use its `pad_flag`
     * @return self 
     * @see self::_setPartColumn()
     */
    public function setFooterColumn(array $contents = array(), $column_index = null, $default = null)
    {
        $this->_setPartColumn($contents, $column_index, $default, 'tfoot');
        return $this;
    }

    /**
     * Add a new column in the table footers
     *
     * @param array $contents The content of the column
     * @param int $column_index The index of the column to set, if `null`, the column will be added at
     *              the end of each line
     * @param mixed $default The default value for empty cells, if `null`, the class will use its `pad_flag`
     * @return self 
     * @see self::_setPartColumn()
     */
    public function addFooterColumn(array $contents = array(), $column_index = null, $default = null)
    {
        $this->_setPartColumn($contents, $column_index, $default, 'tfoot', 'insert');
        return $this;
    }

    /**
     * Set a table footer cell
     *
     * @param array $content The content of the cell
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the footers'last line
     * @param int $cell_index The index of the cell to set in the line, if `null`, the cell will 
     *              be added at the end of the line
     * @return self 
     * @see self::_setPartCell()
     */
    public function setFooterCell($content, $line_index = null, $cell_index = null)
    {
        $this->_setPartCell($content, $line_index, $cell_index, 'tfoot');
        return $this;
    }

    /**
     * Add a new table footer cell
     *
     * @param array $content The content of the cell
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the footers'last line
     * @param int $cell_index The index of the cell to set in the line, if `null`, the cell will 
     *              be added at the end of the line
     * @return self 
     * @see self::_setPartCell()
     */
    public function addFooterCell($content, $line_index = null, $cell_index = null)
    {
        $this->_setPartCell($content, $line_index, $cell_index, 'tfoot', 'insert');
        return $this;
    }

    /**
     * Get the table footers lines
     *
     * @return array The lines array
     * @see self::_getPart()
     */
    public function getFooter()
    {
        return $this->_getPart('tfoot');
    }

    /**
     * Get a table footers line
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @return array The corresponding line
     * @see self::_getPartLine()
     */
    public function getFooterLine($line_index = null)
    {
        return $this->_getPartLine($line_index, 'tfoot');
    }

    /**
     * Get a table footers column
     *
     * @param int $column_index The index of the column to get, if `null`, the last line is returned
     * @return array The corresponding column
     * @see self::_getPartColumn()
     */
    public function getFooterColumn($column_index = null)
    {
        return $this->_getPartColumn($column_index, 'tfoot');
    }

    /**
     * Get a table footers cell
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @param int $cell_index The index of the cell to get in the line, if `null`, the last cell is returned
     * @return array The corresponding cell
     * @see self::_getPartCell()
     */
    public function getFooterCell($line_index = null, $cell_index = null)
    {
        return $this->_getPartCell($line_index, $cell_index, 'tfoot');
    }

    /**
     * Get the table columns size
     *
     * @return int
     */
    public function getTableColumnSize()
    {
        if ($this->column_size===0 && $this->line_size===0 && $this->cell_size===0) {
            $this->_parseTableSizes();
        }
        return $this->column_size;
    }

    /**
     * Get the table lines size
     *
     * @return int
     */
    public function getTableLineSize()
    {
        if ($this->column_size===0 && $this->line_size===0 && $this->cell_size===0) {
            $this->_parseTableSizes();
        }
        return $this->line_size;
    }

    /**
     * Get the table cells size
     *
     * @return int
     */
    public function getTableCellSize()
    {
        if ($this->column_size===0 && $this->line_size===0 && $this->cell_size===0) {
            $this->_parseTableSizes();
        }
        return $this->cell_size;
    }

    /**
     * Get a string information presenting an overview of the table
     *
     * @return string
     */
    public function getSizesInfos()
    {
        if ($this->column_size===0 && $this->line_size===0 && $this->cell_size===0) {
            $this->_parseTableSizes();
        }
        return sprintf(self::$default_foot_mask, $this->getColumnSize(), $this->getLineSize(), $this->getCellSize());
    }

// --------------------
// Internal Setters / Getters
// --------------------

    /**
     * Set a table part lines
     *
     * The `$contents` parameter may be an array of arrays where each item is a line, or a simple
     * array that will be considered as 1 line.
     *
     * @param array $contents The array of footer lines
     * @param string $part One of the table `$_table_parts`
     * @return void
     * @throws \InvalidArgumentException if the part doesn't exist in the table
     */
    protected function _setPart(array $contents, $part)
    {
        if (property_exists($this, $part)) {
            $this->{$part} = $this->_getSetOfLines($contents);
            $this->_parseTableSizes(true);
        } elseif (!in_array($part, self::$_table_parts)) {
            throw new InvalidArgumentException(
                sprintf('Unknown table part "%s"!', $part)
            );
        }
    }

    /**
     * Set a single table part line
     *
     * @param array $contents The content of the line
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the end of the table part
     * @param string $part One of the table `$_table_parts`
     * @param string $action An action in "insert/replace"
     * @return void
     * @throws \InvalidArgumentException if the part doesn't exist in the table
     */
    protected function _setPartLine(array $contents, $line_index, $part, $action = 'replace')
    {
        if (property_exists($this, $part)) {
            if (is_null($line_index)) {
                end($this->{$part});
                $line_index = key($this->{$part})+1;
            } else {
                $line_index--;
            }
            if ($line_index<$this->getLineSize() && 'insert'===$action) {
                $table_part = $this->{$part};
                array_splice($table_part, $line_index, 0, array($this->_getPaddedLine($contents)));
                $this->{$part} = $table_part;
                $this->_repadAllLines();
            } else {
                $this->{$part}[$line_index] = $this->_getPaddedLine($contents);
            }
            $this->_parseTableSizes(true);
        } elseif (!in_array($part, self::$_table_parts)) {
            throw new InvalidArgumentException(
                sprintf('Unknown table part "%s"!', $part)
            );
        }
    }

    /**
     * Set a single table part column
     *
     * @param array $contents The content of the column
     * @param int $column_index The index of the column to set, if `null`, the column will be added at
     *              the end of each line
     * @param mixed $default The default value for empty cells, if `null`, the class will use its `pad_flag`
     * @param string $part One of the table `$_table_parts`
     * @param string $action An action in "insert/replace"
     * @return void
     * @throws \InvalidArgumentException if the part doesn't exist in the table
     */
    protected function _setPartColumn(array $contents, $column_index, $default, $part, $action = 'replace')
    {
        if (property_exists($this, $part)) {
            if (is_null($column_index) || $column_index>$this->getColumnSize()) {
                $column_index = $this->getColumnSize();
            } else {
                $column_index--;
            }
            foreach ($this->{$part} as $i=>$line) {
                $value = isset($contents[$i]) ? $contents[$i] : $default;
                if ($column_index<$this->getColumnSize() && 'insert'===$action) {
                    array_splice($line, $column_index, 0, array($value));
                    $this->{$part}[$i] = $line;
                } else {
                    $line[$column_index] = $value;
                    $this->{$part}[$i] = $line;
                }
            }
            $this->_repadAllLines();
        } elseif (!in_array($part, self::$_table_parts)) {
            throw new InvalidArgumentException(
                sprintf('Unknown table part "%s"!', $part)
            );
        }
    }

    /**
     * Set a single table part cell
     *
     * @param array $content The content of the line
     * @param int $line_index The index of the line to set, if `null`, the line will be added at
     *              the table part's last line
     * @param int $cell_index The index of the cell to set in the line, if `null`, the cell will 
     *              be added at the end of the line
     * @param string $part One of the table `$_table_parts`
     * @param string $action An action in "insert/replace"
     * @return void
     * @throws \InvalidArgumentException if the part doesn't exist in the table
     */
    protected function _setPartCell($content, $line_index, $cell_index, $part, $action = 'replace')
    {
        if (property_exists($this, $part)) {
            if (is_null($line_index)) {
                end($this->{$part});
                $line_index = key($this->{$part});
            } else {
                $line_index--;
            }
            if (is_null($cell_index)) {
                if (isset($this->{$part}[$line_index])) {
                    end($this->{$part}[$line_index]);
                    $cell_index = key($this->{$part}[$line_index]);
                } else {
                    $cell_index = 0;
                }
            } else {
                $cell_index--;
            }
            if ($cell_index<$this->getCellSize() && 'insert'===$action) {
                $line = $this->{$part}[$line_index];
                array_splice($line, $cell_index, 0, array($content));
                $this->{$part}[$i] = $line;
                $this->_repadAllLines();
            } else {
                $this->{$part}[$line_index][$cell_index] = $content;
            }
            $this->_parseTableSizes(true);
        } elseif (!in_array($part, self::$_table_parts)) {
            throw new InvalidArgumentException(
                sprintf('Unknown table part "%s"!', $part)
            );
        }
    }

    /**
     * Get a table part lines array
     *
     * @param string $part One of the table `$_table_parts`
     * @return array The lines array
     * @throws \InvalidArgumentException if the part doesn't exist in the table
     */
    protected function _getPart($part)
    {
        if (property_exists($this, $part)) {
            return $this->{$part};
        } elseif (!in_array($part, self::$_table_parts)) {
            throw new InvalidArgumentException(
                sprintf('Unknown table part "%s"!', $part)
            );
        }
    }

    /**
     * Get a single table part line
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @param string $part One of the table `$_table_parts`
     * @return array The corresponding line
     * @throws \InvalidArgumentException if the part doesn't exist in the table
     */
    protected function _getPartLine($line_index, $part)
    {
        if (property_exists($this, $part)) {
            if (is_null($line_index)) {
                $column_index = $this->getLineSize();
            } else {
                $line_index--;
            }
            return isset($this->{$part}[$line_index]) ? $this->{$part}[$line_index] : null;
        } elseif (!in_array($part, self::$_table_parts)) {
            throw new InvalidArgumentException(
                sprintf('Unknown table part "%s"!', $part)
            );
        }
    }

    /**
     * Get a column of the table body
     *
     * @param int $column_index The index of the column to get, if `null`, the last column is returned
     * @param string $part One of the table `$_table_parts`
     * @return array|null
     * @throws \InvalidArgumentException if the part doesn't exist in the table
     */
    protected function _getPartColumn($column_index, $part)
    {
        if (property_exists($this, $part)) {
            if (is_null($column_index)) {
                $column_index = $this->getColumnSize();
            } else {
                $column_index--;
            }
            $column = array();
            foreach($this->tbody as $line) {
                $column[] = isset($line[$column_index]) ? $line[$column_index] : '';
            }
            return $column;
        } elseif (!in_array($part, self::$_table_parts)) {
            throw new InvalidArgumentException(
                sprintf('Unknown table part "%s"!', $part)
            );
        }
    }

    /**
     * Get a single table part cell
     *
     * @param int $line_index The index of the line to get, if `null`, the last line is returned
     * @param int $cell_index The index of the cell to get in the line, if `null`, the last cell is returned
     * @param string $part One of the table `$_table_parts`
     * @return array The corresponding cell
     * @throws \InvalidArgumentException if the part doesn't exist in the table
     */
    protected function _getPartCell($line_index, $cell_index, $part)
    {
        if (property_exists($this, $part)) {
            if (is_null($line_index)) {
                $line_index = $this->getLineSize();
            } else {
                $line_index--;
            }
            if (is_null($cell_index)) {
                if (isset($this->{$part}[$line_index])) {
                    end($this->{$part}[$line_index]);
                    $cell_index = key($this->{$part}[$line_index]);
                } else {
                    $cell_index = 0;
                }
            } else {
                $cell_index--;
            }
            return isset($this->{$part}[$line_index][$cell_index]) ?
                $this->{$part}[$line_index][$cell_index] : null;
        } elseif (!in_array($part, self::$_table_parts)) {
            throw new InvalidArgumentException(
                sprintf('Unknown table part "%s"!', $part)
            );
        }
    }

// --------------------
// Process
// --------------------

    /**
     * This rebuilds an array to a multi-dimensional array of arrays if necessary
     *
     * This method is used to transform a single line array to an array of one line.
     *
     * @param array $content The content to transform
     * @return array Returns the content array of arrays
     */
    protected function _getSetOfLines($content)
    {
        if (!is_array($content)) $content = array( $content );
        reset($content);
        if (!is_array(current($content))) {
            $content = array( 0=>$content );
        }
        return $content;
    }

    /**
     * Recalculation of each line when the cell count has changed
     *
     * @return void
     */
    protected function _repadAllLines()
    {
        $this->_parseTableSizes(true);
        foreach (self::$_table_parts as $part) {
            if (!empty($this->{$part}) && is_array($this->{$part})) {
                foreach ($this->{$part} as $l=>$part_line) {
                    if (!empty($part_line) && count($part_line)!==$this->getColumnSize()) {
                        $this->{$part}[$l] = $this->_getPaddedLine($part_line);
                    }
                }
            }
        }
    }
    
    /**
     * This completes a line if necessary with empty cells
     *
     * @param array $content The content to transform
     * @return array Returns the content array of arrays
     */
    protected function _getPaddedLine($content)
    {
        if (!is_array($content)) $content = array( $content );
        if ($this->column_size===0 && $this->line_size===0 && $this->cell_size===0) {
            $this->_parseTableSizes();
        }
        if (count($content) > $this->getColumnSize()) {
            $this->_repadAllLines();
        } elseif (count($content) < $this->getColumnSize() && ($this->getPadFlag() & self::PAD_BY_EMPTY_CELLS)) {
            $content = array_pad($content, $this->getColumnSize(), '');
        }
        return $content;
    }

    /**
     * Reset the table sizes
     *
     * @return void
     */
    public function _resetSizes()
    {
        $this->column_size = 0;
        $this->line_size = 0;
        $this->cell_size = 0;
    }

    /**
     * Calculation of all table sizes
     *
     * @param bool $reset Reset all sizes before (default is `false`)
     * @return void
     */
    protected function _parseTableSizes($reset = false)
    {
        if ($reset) $this->_resetSizes();
        $this->line_size = count($this->tbody);
        foreach (self::$_table_parts as $part) {
            if (!empty($this->{$part}) && is_array($this->{$part})) {
                foreach ($this->{$part} as $part_line) {
                    $line_cells_count = 0;
                    foreach ($part_line as $part_cell) {
                        $line_length = strlen($part_cell)+2;
                        if ($line_length > $this->cell_size) {
                            $this->cell_size = $line_length;
                        }
                        $line_cells_count++;
                    }
                    if ($line_cells_count > $this->column_size) {
                        $this->column_size = $line_cells_count;
                    }
                }
            }
        }
    }

// --------------------
// Special text rendering
// --------------------

    /**
     * Plain text rendering of the table
     *
     * @param int $str_pad_flag One of the PHP internal `str_pad()` `$pad_type`
     * @return string
     * @see str_pad()
     */
    public function render($str_pad_flag = STR_PAD_RIGHT)
    {
        $stacks = array();
        if ($this->column_size===0 && $this->line_size===0 && $this->cell_size===0) {
            $this->_parseTableSizes();
        }

        foreach (self::$_table_parts as $part) {
            if (!empty($this->{$part}) && is_array($this->{$part})) {
                $stacks[] = 'hseparator';
                foreach ($this->{$part} as $part_line) {
                    $stack_line = array();
                    $stack_line[] = 'vseparator';
                    foreach ($part_line as $i=>$part_cell) {
                        if (count($part_line)<$this->getColumnSize() && $i===count($part_line)-1) {
                            $stack_line[] = str_pad(' '.$part_cell, 
                                ( ($this->getColumnSize() - count($part_line) + 1) * $this->getCellSize())
                                     + ($this->getColumnSize() - count($part_line)), 
                                ' ', $str_pad_flag);
                        } else {
                            $stack_line[] = ' '.$part_cell.' ';
                        }
                        $stack_line[] = 'vseparator';
                    }
                    $stacks[] = $stack_line;
                }
            }
        }
        $stacks[] = 'hseparator';

        // special footer if no footer
        if (!count($this->tfoot)) {
            $stacks[] = array(
                'vseparator',
                str_pad(' '.$this->getSizesInfos(), ($this->getColumnSize() * $this->getCellSize())
                    + ($this->getColumnSize() - 1), ' ', $str_pad_flag),
                'vseparator'
            );
            $stacks[] = 'hseparator';
        }

        $str = '';
        if (!empty($this->title)) {
            $str .= $this->title."\n";
        }
        foreach ($stacks as $line) {
            $str .= "\n";
            if (is_array($line)) {
                foreach ($line as $cell) {
                    if ('vseparator'===$cell) {
                        $str .= '|';
                    } else {
                        $str .= str_pad($cell, $this->getCellSize(), ' ', $str_pad_flag);
                    }
                }
            } elseif ('hseparator'===$line) {
                for ($i=0; $i<$this->getColumnSize(); $i++) {
                    $str .= str_pad('+', ($this->getCellSize() + 1), '-');
                }
                $str .= '+';
            }
        }        
        return $str;
    }

// --------------------
// Setters / Getters aliases for convenience
// --------------------

    /**
     * @see self::setBody()
     */
    public function setContents()
    {
        return call_user_func_array(array($this, 'setBody'), func_get_args());
    }

    /**
     * @see self::setBodyLine()
     */
    public function setContentLine()
    {
        return call_user_func_array(array($this, 'setBodyLine'), func_get_args());
    }

    /**
     * @see self::addBodyLine()
     */
    public function addContentLine()
    {
        return call_user_func_array(array($this, 'addBodyLine'), func_get_args());
    }

    /**
     * @see self::setBodyColumn()
     */
    public function setContentColumn()
    {
        return call_user_func_array(array($this, 'setBodyColumn'), func_get_args());
    }

    /**
     * @see self::setBodyColumn()
     */
    public function setContentCol()
    {
        return call_user_func_array(array($this, 'setBodyColumn'), func_get_args());
    }

    /**
     * @see self::setBodyColumn()
     */
    public function setBodyCol()
    {
        return call_user_func_array(array($this, 'setBodyColumn'), func_get_args());
    }

    /**
     * @see self::addBodyColumn()
     */
    public function addContentColumn()
    {
        return call_user_func_array(array($this, 'addBodyColumn'), func_get_args());
    }

    /**
     * @see self::addBodyColumn()
     */
    public function addContentCol()
    {
        return call_user_func_array(array($this, 'addBodyColumn'), func_get_args());
    }

    /**
     * @see self::addBodyColumn()
     */
    public function addBodyCol()
    {
        return call_user_func_array(array($this, 'addBodyColumn'), func_get_args());
    }

    /**
     * @see self::setBodyCell()
     */
    public function setContentCell()
    {
        return call_user_func_array(array($this, 'setBodyCell'), func_get_args());
    }

    /**
     * @see self::addBodyCell()
     */
    public function addContentCell()
    {
        return call_user_func_array(array($this, 'addBodyCell'), func_get_args());
    }

    /**
     * @see self::getBody()
     */
    public function getContents()
    {
        return call_user_func_array(array($this, 'getBody'), func_get_args());
    }

    /**
     * @see self::getBodyLine()
     */
    public function getContentLine()
    {
        return call_user_func_array(array($this, 'getBodyLine'), func_get_args());
    }

    /**
     * @see self::getBodyColumn()
     */
    public function getContentColumn()
    {
        return call_user_func_array(array($this, 'getBodyColumn'), func_get_args());
    }

    /**
     * @see self::getBodyColumn()
     */
    public function getContentCol()
    {
        return call_user_func_array(array($this, 'getBodyColumn'), func_get_args());
    }

    /**
     * @see self::getBodyColumn()
     */
    public function getBodyCol()
    {
        return call_user_func_array(array($this, 'getBodyColumn'), func_get_args());
    }

    /**
     * @see self::getBodyCell()
     */
    public function getContentCell()
    {
        return call_user_func_array(array($this, 'getBodyCell'), func_get_args());
    }

    /**
     * @see self::getTableColumnSize()
     */
    public function getTableColSize()
    {
        return call_user_func_array(array($this, 'getTableColumnSize'), func_get_args());
    }

    /**
     * @see self::getBodyColumn()
     */
    public function getCol()
    {
        return call_user_func_array(array($this, 'getBodyColumn'), func_get_args());
    }

    /**
     * @see self::getHeaderColumn()
     */
    public function getHeaderCol()
    {
        return call_user_func_array(array($this, 'getHeaderColumn'), func_get_args());
    }

    /**
     * @see self::addHeaderColumn()
     */
    public function addHeaderCol()
    {
        return call_user_func_array(array($this, 'addHeaderColumn'), func_get_args());
    }

    /**
     * @see self::setHeaderColumn()
     */
    public function setHeaderCol()
    {
        return call_user_func_array(array($this, 'setHeaderColumn'), func_get_args());
    }

    /**
     * @see self::getFooterColumn()
     */
    public function getFooterCol()
    {
        return call_user_func_array(array($this, 'getFooterColumn'), func_get_args());
    }

    /**
     * @see self::addFooterColumn()
     */
    public function addFooterCol()
    {
        return call_user_func_array(array($this, 'addFooterColumn'), func_get_args());
    }

    /**
     * @see self::setFooterColumn()
     */
    public function setFooterCol()
    {
        return call_user_func_array(array($this, 'setFooterColumn'), func_get_args());
    }

    /**
     * @see self::getTableColumnSize()
     */
    public function getColumnSize()
    {
        return call_user_func_array(array($this, 'getTableColumnSize'), func_get_args());
    }

    /**
     * @see self::getTableColumnSize()
     */
    public function getColSize()
    {
        return call_user_func_array(array($this, 'getTableColumnSize'), func_get_args());
    }

    /**
     * @see self::getTableLineSize()
     */
    public function getLineSize()
    {
        return call_user_func_array(array($this, 'getTableLineSize'), func_get_args());
    }

    /**
     * @see self::getTableCellSize()
     */
    public function getCellSize()
    {
        return call_user_func_array(array($this, 'getTableCellSize'), func_get_args());
    }

}

// Endfile
