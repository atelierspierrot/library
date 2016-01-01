<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
 */

namespace Library\Tool;

/**
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class Pagination
    implements \Iterator
{

    /**
     * @var int Current page offset
     */
    protected $offset       = 0;

    /**
     * @var int One page limit
     */
    protected $limit        = null;

    /**
     * @var int Number of pages
     */
    protected $pages_number = 1;

    /**
     * @var int Number of items
     */
    protected $items_number = 0;

    /**
     * @var array   Collection to paginate
     */
    protected $collection   = array();

    /**
     * @var bool    Flag to check if the object is processed
     */
    private $__isProcessed  = false;

    /**
     * @var int Current page offset during iteration
     */
    protected $iterator_offset = 0;

    /**
     * @param array $collection
     * @param int $limit
     * @param int $offset
     */
    public function __construct(array $collection = array(), $limit = null, $offset = 0)
    {
        $this
            ->setCollection($collection)
            ->setLimit($limit)
            ->setOffset($offset)
            ->_process()
            ;
    }

// -----------------
// Setters / Getters
// -----------------

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function setPagesNumber($pages_number)
    {
        $this->pages_number = $pages_number;
        return $this;
    }

    public function setItemsNumber($items_number)
    {
        $this->items_number = $items_number;
        return $this;
    }

    public function setCollection(array $collection)
    {
        $this->collection = $collection;
        $this->__isProcessed = false;
        $this->setItemsNumber(count($collection));
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getPagesNumber()
    {
        return $this->pages_number;
    }

    public function getItemsNumber()
    {
        return $this->items_number;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getPaginatedCollection()
    {
        $this->_process();
        $offset = $this->getOffset();
        $limit  = $this->getLimit();
        if (!is_null($limit)) {
            return array_slice($this->getCollection(), $offset, $limit);
        } else {
            return $this->getCollection();
        }
    }

    public function setOffsetByPageNumber($page_number)
    {
        $this->_process();
        $this->setOffset(round($page_number * $this->getLimit()));
        return $this;
    }

    public function exists()
    {
        $this->_process();
        return ($this->getPagesNumber()>1);
    }

// -----------------
// Process
// -----------------

    protected function _process()
    {
        if ($this->__isProcessed === true) {
            return $this;
        }

        $limit = $this->getLimit();
        if (is_null($limit)) {
            $this->setPagesNumber(1);
            $this->setOffset(0);
        } else {
            $this->setPagesNumber(round($this->getItemsNumber() / $this->getLimit()));
        }

        $this->__isProcessed = true;
        return $this;
    }

// -----------------
// Iterator Interface
// -----------------

    public function current ()
    {
        return $this;
    }

    public function key ()
    {
        return $this->iterator_offset;
    }

    public function next ()
    {
        $this->iterator_offset += $this->getLimit();
    }

    public function rewind ()
    {
        $this->_process();
        $this->iterator_offset = 0;
    }

    public function valid ()
    {
        return ($this->getPageNumber() <= $this->getPagesNumber());
    }

    public function isFirst()
    {
        return ($this->iterator_offset < $this->getLimit());
    }

    public function isLast()
    {
        return (round($this->iterator_offset + $this->getLimit()) >= $this->getItemsNumber());
    }

    public function isCurrent()
    {
        return ($this->iterator_offset == $this->getOffset());
    }

    public function getPageNumber()
    {
        return round($this->iterator_offset / $this->getLimit())+1;
    }

}

