<?php
/**
 * This file is part of the Library package.
 *
 * Copyright (c) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
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