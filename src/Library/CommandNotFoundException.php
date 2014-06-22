<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library;

use \RuntimeException;

class CommandNotFoundException
    extends RuntimeException
{

    public function __construct($command = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct(
            sprintf('The required binary command "%s" can\'t be found in your system!', $command),
            $code, $previous
        );
    }

}

// Endfile
