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

namespace Library;

/**
 * @author  Piero Wbmstr <me@e-piwi.fr>
 */
interface FactoryInterface
{

    /**
     * Constant to use to not throw error if a class is not found or doesn't implement or extend some requirements
     */
    const GRACEFULLY_FAILURE = 1;

    /**
     * Constant to use to throw an error if a class is not found or doesn't implement or extend some requirements
     */
    const ERROR_ON_FAILURE = 2;

    /**
     * Build the object instance following current factory settings
     *
     * Errors are thrown by default but can be "gracefully" skipped using the flag `GRACEFULLY_FAILURE`.
     * In all cases, error messages are loaded in final parameter `$logs` passed by reference.
     *
     * @param string $name
     * @param array $parameters
     * @param int $flag One of the class constants flags
     * @param array $logs Passed by reference
     * @return object
     */
    public function build($name, array $parameters = null, $flag = self::ERROR_ON_FAILURE, array &$logs = array());

    /**
     * Find the object builder class following current factory settings
     *
     * Errors are thrown by default but can be "gracefully" skipped using the flag `GRACEFULLY_FAILURE`.
     * In all cases, error messages are loaded in final parameter `$logs` passed by reference.
     *
     * @param string $name
     * @param int $flag One of the class constants flags
     * @param array $logs Passed by reference
     * @return null|string
     */
    public function findBuilder($name, $flag = self::ERROR_ON_FAILURE, array &$logs = array());

}

// Endfile