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


namespace Library\Object;

use \OutOfBoundsException;

/**
 * @author  piwi <me@e-piwi.fr>
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
     * @param \Exception $previous The previous catched excpetion
     */
    public function __construct($property_name = '', $object_name = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct(
            sprintf('Direct access to property "%s" on object "%s" is not allowed!', $property_name, $object_name),
            $code, $previous
        );
    }
}
