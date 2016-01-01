<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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


namespace Library\CommandLine;

/**
 * CommandLine controller interface
 *
 * @author  piwi <me@e-piwi.fr>
 */
interface CommandLineControllerInterface
{

    /**
     * Adding a method in the collection of done methods
     * @param   string  $method_name    The name of the method
     */
    public function addDoneMethod($method_name);

    /**
     * Get the collection of done methods
     * @return  array
     */
    public function getDoneMethods();

    /**
     * Set the current command line script called
     * @param   string  $script_name    The script name
     */
    public function setScript($script_name);

    /**
     * Get the current command line script called
     * @return  string|null
     */
    public function getScript();

    /**
     * Set the command line parameters
     * @param   array   $params     The collection of parameters
     */
    public function setParameters(array $params);

    /**
     * Get the parameters collection
     * @return  array
     */
    public function getParameters();
}
