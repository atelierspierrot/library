<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library;

/**
 * @author 		Piero Wbmstr <me@e-piwi.fr>
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
     *
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
     *
     * @return null|string
     */
    public function findBuilder($name, $flag = self::ERROR_ON_FAILURE, array &$logs = array());

}

// Endfile