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

namespace Library\StaticConfiguration;

/**
 * Config class interface to use with \Library\StaticConfiguration\Config
 *
 * @author  Piero Wbmstr <me@e-piwi.fr>
 */
interface ConfiguratorInterface
{

    /**
     * Get the default configuration values
     *
     * This must define at least the requires entries defined below.
     * During the configuration object lifecycle, no other entry than these defaults
     * could be define.
     *
     * @return array
     */
    public static function getDefaults();

    /**
     * Get the required configuration entries
     *
     * @return array
     */
    public static function getRequired();

}

// Endfile