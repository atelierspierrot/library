<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library;

/**
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
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
     * Load an object following the factory settings
     *
     * Errors are thrown by default but can be "gracefully" skipped using the flag `GRACEFULLY_FAILURE`
     *
     * @param string $name
     * @param array $parameters
     * @param int $flag One of the class constants flags
     * @param array $options
     *
     * @return object
     */
    public function build($name, array $parameters = null, $flag = self::ERROR_ON_FAILURE, array $options = null);

}

// Endfile