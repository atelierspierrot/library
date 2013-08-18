<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library;

use \Library\FactoryInterface;
use \Patterns\Abstracts\AbstractStaticCreator;
use \Library\Helper\Code as CodeHelper;
use \Library\Helper\Text as TextHelper;
use \ReflectionClass;

/**
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Factory
    extends AbstractStaticCreator
    implements FactoryInterface
{

	/**
	 * @var string A name to identify factory error messages
	 */
	protected $factory_name = '';

	/**
	 * @var string Method to call for object construction
	 */
	protected $call_method = '__construct';

	/**
	 * @var string Printf expression (%s will be replaced by the `$name` in CamelCase)
	 */
	protected $class_name_mask = array('%s');

	/**
	 * @var array
	 */
	protected $default_namespace = array();

	/**
	 * @var array
	 */
	protected $mandatory_namespace = array();

	/**
	 * @var array
	 */
	protected $must_implement = array();

	/**
	 * @var array
	 */
	protected $must_extend = array();

	/**
	 * @var array
	 */
	protected $must_implement_or_extend = array();

	/**
	 * @var array
	 */
	protected $must_implement_and_extend = array();

	/**
	 * @param array $options
	 */
	public function init(array $options = null)
	{
	    if (!empty($options)) {
	        $this->setOptions($options);
	    }
	}

    /**
     * Magic method to allow usage of `$factory->mustImplementOrExtend()` for each property
     *
     * @param string $name
     * @param array $arguments
     *
     * @return self
     */
    public function __call($name, array $arguments)
    {
        $property_name = CodeHelper::getPropertyName($name);
        if (property_exists($this, $property_name)) {
            $param = array_shift($arguments);
            if (is_array($this->{$property_name})) {
                $this->{$property_name} = is_array($param) ? $param : array($param);
            } else {
                $this->{$property_name} = $param;
            }
        }
        return $this;
    }

    /**
     * Set the object options
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options)
    {
        foreach ($options as $index=>$val) {
            if (property_exists($this, $index)) {
                $this->{$index} = $val;
            }
        }
        return $this;
    }

    /**
     * Load an object following the factory settings
     *
     * Errors are thrown by default but can be "gracefully" skipped using the flag `GRACEFULLY_FAILURE`
     *
     * @param string $name
     * @param array $parameters
     * @param int $flag One of the class constants flags
     * @param array $options
     *
     * @return object
     *
     * @throws RuntimeException if the class is not found
     * @throws RuntimeException if the class doesn't implement or extend some required dependencies
     * @throws RuntimeException if the class method for construction is not callable
     */
    public function build($name, array $parameters = null, $flag = self::ERROR_ON_FAILURE, array $options = null)
    {
        $object = null;
	    if (!empty($options)) {
	        $this->setOptions($options);
	    }

        $cc_name = array(TextHelper::toCamelCase($name));
        if (!$this->_findClasses($cc_name)) {
            $cc_name = $this->_buildClassesNames($cc_name, $this->class_name_mask);
        }

        if (!$this->_findClasses($cc_name)) {
            $namespaces = array();
            if (!empty($this->default_namespace)) {
                $namespaces = array_merge($namespaces, $this->default_namespace);
            }
            if (!empty($this->mandatory_namespace)) {
                $namespaces = array_merge($namespaces, $this->mandatory_namespace);
            }
            if (!empty($namespaces)) {
                $cc_name = $this->_addNamespaces($cc_name, $namespaces);
            }
        }

        if (false!==$_cls = $this->_findClasses($cc_name)) {
        
            // required namespace
            if (!empty($this->mandatory_namespace) && !$this->_classesInNamespaces($_cls, $this->mandatory_namespace)) {
                if ($flag & self::ERROR_ON_FAILURE) {
                    throw new \RuntimeException(
                        $this->_getErrorMessage('Class "%s" must be in namespace "%s"!', $_cls, array_shift($this->mandatory_namespace))
                    );
                }
                return $object;
            }

            // required interface
            if (!empty($this->must_implement) && !$this->_classesImplements($_cls, $this->must_implement)) {
                if ($flag & self::ERROR_ON_FAILURE) {
                    throw new \RuntimeException(
                        $this->_getErrorMessage('Class "%s" must implement interface "%s"!', $_cls, array_shift($this->must_implement))
                    );
                }
                return $object;
            }

            // required inheritance
            if (!empty($this->must_extend) && !$this->_classesExtends($_cls, $this->must_extend)) {
                if ($flag & self::ERROR_ON_FAILURE) {
                    throw new \RuntimeException(
                        $this->_getErrorMessage('Class "%s" must extend class "%s"!', $_cls, array_shift($this->must_extend))
                    );
                }
                return $object;
            }

            // required interface OR inheritance
            if (!empty($this->must_implement_or_extend) &&
                !$this->_classesImplements($_cls, $this->must_implement_or_extend) &&
                !$this->_classesExtends($_cls, $this->must_implement_or_extend)
            ) {
                if ($flag & self::ERROR_ON_FAILURE) {
                    throw new \RuntimeException(
                        $this->_getErrorMessage('Class "%s" doesn\'t implement or extend required interfaces or classes (%s)!', 
                            $_cls, implode(', ', $this->must_implement_or_extend))
                    );
                }
                return $object;
            }

            // required interface AND inheritance
            if (!empty($this->must_implement_and_extend) && (
                !$this->_classesImplements($_cls, $this->must_implement_and_extend) ||
                !$this->_classesExtends($_cls, $this->must_implement_and_extend)
            )) {
                if ($flag & self::ERROR_ON_FAILURE) {
                    throw new \RuntimeException(
                        $this->_getErrorMessage('Class "%s" doesn\'t implement and extend required interfaces or classes (%s)!', 
                            $_cls, implode(', ', $this->must_implement_and_extend))
                    );
                }
                return $object;
            }

            // object creation
            $reflection_obj = new ReflectionClass($_cls);
            if (
                method_exists($_cls, '__construct') &&
                is_callable(array($_cls, '__construct'))
            ) {
                $_caller = call_user_func_array(array($reflection_obj, 'newInstance'), $parameters);
            } else {
                try {
                    $_caller = new $_cls($parameters);
                } catch (Exception $e) {
                    if ($flag & self::ERROR_ON_FAILURE) {
                        throw new \RuntimeException(
                            $this->_getErrorMessage('Constructor method for class "%s" is not callable!', $_cls)
                        );
                    }
                }
            }
            if ($this->call_method==='__construct') {
                $object = $_caller;
            } else {
                if (
                    method_exists($_caller, $this->call_method) &&
                    is_callable(array($_caller, $this->call_method))
                ) {
                    if ($reflection_obj->getMethod($this->call_method)->isStatic()) {
                        $object = call_user_func_array(array($_cls, $this->call_method), $parameters);
                    } else {
                        $object = call_user_func_array(array($_caller, $this->call_method), $parameters);
                    }
                } else {
                    if ($flag & self::ERROR_ON_FAILURE) {
                        throw new \RuntimeException(
                            $this->_getErrorMessage('Method "%s" for factory construction of class "%s" is not callable!', $this->call_method, $_cls)
                        );
                    }
                }
            }

        } elseif ($flag & self::ERROR_ON_FAILURE) {
            throw new \RuntimeException(
                $this->_getErrorMessage('No matching class found for factory build "%s" (searched in %s)!',
                    $name, implode(', ', $cc_name))
            );
        }

        return $object;
    }

// -----------------------
// Processes
// -----------------------

    /**
     * Build the class name filling the `$class_name_mask`
     *
     * @param string|array $class_names
     *
     * @return misc The found class name if it exists, false otherwise
     */
    protected function _findClasses($class_names)
    {
        if (!is_array($class_names)) {
            $class_names = array($class_names);
        }
        foreach ($class_names as $_cls) {
            if (true===@class_exists($_cls)) {
                return $_cls;
            }
        }
        return false;
    }

    /**
     * Build the class name filling a set of masks
     *
     * @param string|array $names
     * @param array $masks
     *
     * @return array
     */
    protected function _buildClassesNames($names, array $masks)
    {
        if (!is_array($names)) {
            $names = array($names);
        }
        $return_names = array();
        foreach ($names as $_name) {
            foreach ($masks as $_mask) {
                $return_names[] = sprintf($_mask, TextHelper::toCamelCase($_name));
            }
        }
        return $return_names;
    }

    /**
     * Add a set of namespaces to a list of class names
     *
     * @param string|array $names
     * @param array $namespaces
     *
     * @return array
     */
    protected function _addNamespaces($names, array $namespaces)
    {
        if (!is_array($names)) {
            $names = array($names);
        }
        $return_names = array();
        foreach ($names as $_name) {
            foreach ($namespaces as $_namespace) {
                $tmp_namespace = rtrim(TextHelper::toCamelCase($_namespace), '\\').'\\';
                $return_names[] = $tmp_namespace.str_replace($tmp_namespace, '', TextHelper::toCamelCase($_name));
            }
        }
        return $return_names;
    }

    /**
     * Test if a set of class names implements a list of interfaces
     *
     * @param string|array $names
     * @param array $interfaces
     *
     * @return bool
     */
    protected function _classesImplements($names, array $interfaces)
    {
        if (!is_array($names)) {
            $names = array($names);
        }
        foreach ($names as $_name) {
            foreach ($interfaces as $_interface) {
                if (@interface_exists($_interface) && CodeHelper::impelementsInterface($_name, $_interface)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Test if a set of class names extends a list of classes
     *
     * @param string|array $names
     * @param array $classes
     *
     * @return bool
     */
    protected function _classesExtends($names, array $classes)
    {
        if (!is_array($names)) {
            $names = array($names);
        }
        foreach ($name as $_name) {
            foreach ($classes as $_class) {
                if (@class_exists($_interface) && CodeHelper::extendsClass($_name, $_class)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Test if a classes names set is in a set of namespaces
     *
     * @param string|array $names
     * @param array $namespaces
     *
     * @return string|bool
     */
    protected function _classesInNamespaces($names, array $namespaces)
    {
        if (!is_array($names)) {
            $names = array($names);
        }
        foreach ($names as $_name) {
            foreach ($namespaces as $_namespace) {
                $tmp_namespace = rtrim(TextHelper::toCamelCase($_namespace), '\\').'\\';
                if (substr_count(TextHelper::toCamelCase($_name), $tmp_namespace)>0) {
                    return $_name;
                }
            }
        }
        return false;
    }

    /**
     * Build a factory error message adding it the `$factory_name` if so
     *
     * @return string
     */
    protected function _getErrorMessage()
    {
        return (!empty($this->factory_name) ? '['.$this->factory_name.'] ' : '')
            .call_user_func_array('sprintf', func_get_args());
    }

}

// Endfile