<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library\Object;

use \OutOfBoundsException;

/**
 * @author      Pierre Cassat & contributors <me@e-piwi.fr>
 */
class InvokableAccessException
    extends OutOfBoundsException
{

    /**
     * Constructor: creation of the parent instance
     *
     * @param string $property_name The name of the property getted
     * @param string $object_name The name of the invokable class object
     * @param int $code The exception code
     * @param Exception $previous The previous catched excpetion
     */
    public function __construct($property_name = '', $object_name = '', $code = 0, Exception $previous = null)
    {
        parent::__construct(
            sprintf('Direct access to property "%s" on object "%s" is not allowed!', $property_name, $object_name),
            $code, $previous
        );
    }

}

// Endfile
