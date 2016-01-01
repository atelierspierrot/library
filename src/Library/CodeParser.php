<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
 */

namespace Library;

use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionFunction;

/**
 * Source code parser
 *
 * @author  piwi <me@e-piwi.fr>
 */
class CodeParser
{

    var $reflection; 
    var $builtInMethods;

    protected $object_name;
    protected $object_type;

    /**
     * For a full class analyze
     */
    const PARSE_CLASS = 1;

    /**
     * For a class method analyze
     *
     * You must set `$object_name` as `class:method`
     */
    const PARSE_METHOD = 2;

    /**
     * For a function analyze
     */
    const PARSE_FUNC = 4;

    /**
     * Construct a code parser object
     *
     * @param string $object_name The name of the object to analyze
     * @param int $object_type A flag that must be a class constant
     */
    public function __construct($object_name = null, $object_type = self::PARSE_CLASS)
    {
        self::set_objectName( $object_name );
        self::set_objectType( $object_type );
    }

// -------------------------
// GETTERS / SETTERS
// -------------------------

    public function set_objectName($object_name = null)
    {
        if (!empty($object_name)) $this->object_name = $object_name;
    }

    public function get_objectName()
    {
        return $this->object_name;
    }

    public function set_objectType($object_type = null)
    {
        if (!empty($object_type)) $this->object_type = $object_type;
    }

    public function get_objectType()
    {
        return $this->object_type;
    }

    public function get_shortDescription($object_name = null, $object_type = null)
    {
        self::set_objectName( $object_name );
        self::set_objectType( $object_type );
        self::__getReflection();
        return self::__extractDocString( $this->reflection->getDocComment(), 1 );
    }

    public function get_longDescription($object_name = null, $object_type = null)
    {
        self::set_objectName( $object_name );
        self::set_objectType( $object_type );
        self::__getReflection();
        return self::__extractDocString( $this->reflection->getDocComment(), array(2) );
    }

// -------------------------
// PROCESS
// -------------------------

    private function __getReflection()
    {
        if (self::get_objectType() & self::PARSE_FUNC) {
            if (function_exists(self::get_objectName()))
                $this->reflection = new ReflectionFunction( self::get_objectName() ); 
            else
                trigger_error( "[CodeParser] Function '".self::get_objectName()."' not found", E_USER_WARNING );
        }
        elseif (self::get_objectType() & self::PARSE_METHOD) {
            $_s = self::get_objectName();
            if (strpos($_s, ':')) {
                list($_class, $_method) = explode(':', $_s, 2);
                if (class_exists($_class))
                    $this->reflection = new ReflectionMethod( $_class, $_method ); 
                else
                    trigger_error( "[CodeParser] Class '".$_class."' not found", E_USER_WARNING );
            } else {
                trigger_error( "[CodeParser] Class and method not found in '".self::get_objectName()."'", E_USER_WARNING );
            }
        }
        elseif (self::get_objectType() & self::PARSE_CLASS) {
            if (class_exists(self::get_objectName()))
                $this->reflection = new ReflectionClass( self::get_objectName() ); 
            else
                trigger_error( "[CodeParser] Class '".self::get_objectName()."' not found", E_USER_WARNING );
        }
    }

    /**
     * Analyzes self methods using reflection
     *
     * @param object $object
     * @return bool
     */
    private function __getMethods($object)
    {
        $reflection = new ReflectionClass($object);
        
        //get all methods
        $methods = $reflection->getMethods();
        $this->builtInMethods = array();
        
        //get properties for each method
        if(!empty($methods))
        {
            foreach ($methods as $method) {
                if(!empty($method->name))
                {
                    $methodProp = new ReflectionMethod($object, $method->name);
                    
                    //saves all methods names found
                    $this->builtInMethods['all'][] = $method->name;
                    
                    //saves all private methods names found
                    if($methodProp->isPrivate()) 
                    {
                        $this->builtInMethods['private'][] = $method->name;
                    }
                    
                    //saves all private methods names found                 
                    if($methodProp->isPublic()) 
                    {
                        $this->builtInMethods['public'][] = $method->name;
                        
                        // gets info about the method and saves them. These info will be used for the xmlrpc server configuration.
                        // (only for public methods => avoids also all the public methods starting with '__')
                        if(!preg_match('/^__/', $method->name, $matches))
                        {
                            // -method name
                            $this->builtInMethods['functions'][$method->name]['function'] = $reflection->getName().'.'.$method->name;
                            
                            // -method docstring
                            $this->builtInMethods['functions'][$method->name]['docstring'] = $this->__extractDocString($methodProp->getDocComment());
                        }
                    }
                }
            }
        } else {
            return false;
        }
        return true;
    }
    
    /**
     * Manipulates a DocString and returns a readable string
     *
     * @param string $DocComment
     * @param int/array $lines Which lines of the comment to get
     * @param bool $parse_tags
     * @return array $_tmp
     */
    private function __extractDocString($DocComment, $lines = null, $parse_tags = false)
    {
        $split = preg_split("/\r\n|\n|\r/", $DocComment);

        //clean up: removes useless chars like new-lines, tabs and *
        $_tmp = array();
        $_l=1;
        foreach ($split as $id => $row) {
            $_row = trim($row, "* /\n\t\r");
            if (!empty($_row) AND $_row[0]!='@') {
                $_tmp[$_l] = $_row;
                $_l++;
            }
        }           

        if (!empty($lines)) {
            if (is_numeric($lines)) {
                if (!isset($_tmp[ $lines ])) return '';
                $_tmp = array( $_tmp[ $lines ] );
            } elseif (is_array($lines)) {
                if (!empty($lines[1]))
                    $_tmp = array_slice($_tmp, array_search( $_tmp[ $lines[0] ], $_tmp)-1, $lines[1]);
                else
                    $_tmp = array_slice($_tmp, array_search( $_tmp[ $lines[0] ], $_tmp)-1 );
            }
        }

        return trim(implode("\n",$_tmp));
    }
    
}

