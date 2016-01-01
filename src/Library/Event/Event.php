<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
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

namespace Library\Event;

/**
 * Class Event
 */
class Event
    implements EventInterface
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var ObservableInterface
     */
    protected $_subject;

    /**
     * @var bool
     */
    private $_propagate = true;

    /**
     * @param ObservableInterface $subject
     * @param string $name
     */
    public function __construct(ObservableInterface $subject, $name)
    {
        $this->_subject = $subject;
        $this->_name    = $name;
    }

    /**
     * @return ObservableInterface
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return bool
     */
    public function isPropagationStopped()
    {
        return (bool) ($this->_propagate===false);
    }

    /**
     * @return $this
     */
    public function stopPropagation()
    {
        $this->_propagate = false;
        return $this;
    }

}

// Endfile