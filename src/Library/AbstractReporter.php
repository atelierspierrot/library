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
 * The base class for all content Reporters
 *
 * @author      Pierre Cassat & contributors <piero.wbmstr@gmail.com>
 */
abstract class AbstractReporter extends ReporterTemplate
{

    /**
     * This must return the actual rendering of the Reporter
     *
     * @param misc $content The content to parse, in various types
     * @return string The result of the parsed content
     */
    abstract public function render($content = null);

}

// Endfile
