<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library;

use \ReflectionClass,
    \ReflectionMethod,
    \ReflectionFunction;

/**
 * Source code parser
 *
 * @author 		Pierre Cassat & contributors <piero.wbmstr@gmail.com>
 */
class CodeParser
{

	var $reflection; 
	var $builtInMethods;

	protected $object_name;
	protected $object_type;

	/**
	 * Construct a code parser object
	 *
	 * "ObjectType" must be :
	 * -    "class" for a full class analyze (default)
	 * -    "method" for a class method analyze ; you must set "objectName" like "class:method"
	 * -    "function" for a function analyze
	 */
	public function __construct($object_name = null, $object_type = 'class')
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
		switch( self::get_objectType() ) 
		{
			case 'class': 
				if (class_exists(self::get_objectName()))
					$this->reflection = new ReflectionClass( self::get_objectName() ); 
				else
					trigger_error( "[CodeParser] Class '".self::get_objectName()."' not found", E_USER_WARNING );
				break;

			case 'method': 
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
				break;

			case 'function': 
				if (function_exists(self::get_objectName()))
					$this->reflection = new ReflectionFunction( self::get_objectName() ); 
				else
					trigger_error( "[CodeParser] Function '".self::get_objectName()."' not found", E_USER_WARNING );
				break;

		}
	}

	/**
	 * 
	 * Analizes self methods using reflection
	 * @return Boolean
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
	 * @param String $DocComment
	 * @param Integer/Array $lines Which lines of the comment to get
	 * @return Array $_tmp
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

// Endfile