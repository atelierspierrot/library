<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\StaticConfiguration;

/**
 * Config class interface to use with Library\StaticConfiguration\Config
 *
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
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