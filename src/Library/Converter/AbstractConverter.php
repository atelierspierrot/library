<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Converter;

/**
 */
abstract class AbstractConverter
{

    /**
     * Process a content conversion
     *
     * @param misc $content
     *
     * @return misc
     */
	abstract public static function convert($content);

}

// Endfile