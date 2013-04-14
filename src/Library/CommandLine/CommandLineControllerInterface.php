<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\CommandLine;

/**
 * CommandLine controller interface
 *
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
 */
interface CommandLineControllerInterface
{

	/**
	 * Adding a method in the collection of done methods
	 *
	 * @param string $mathod_name The name of the method
	 */
	public function addDoneMethod($method_name);

	/**
	 * Get the collection of done methods
	 */
	public function getDoneMethods();

	/**
	 * Set the current command line script called
	 *
	 * @param string $script_name The script name
	 */
	public function setScript($script_name);

	/**
	 * Get the current command line script called
	 */
	public function getScript();

	/**
	 * Set the command line parameters
	 *
	 * @param array $params The collection of parameters
	 */
	public function setParameters(array $params);

	/**
	 * Get the parameters collection
	 */
	public function getParameters();

}

// Endfile