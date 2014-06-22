<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library\CommandLine;

/**
 * CommandLine controller interface
 *
 * @author  Piero Wbmstr <me@e-piwi.fr>
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

// Endfile