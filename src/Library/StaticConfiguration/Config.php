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

namespace Library\StaticConfiguration;

/**
 * Static global configuration object manager
 *
 * @author  Piero Wbmstr <me@e-piwi.fr>
 */
class Config
{

    /**
     * The global configuration object
     * @var \Library\StaticConfiguration\ConfiguratorInterface
     */
    private static $__configurator;

    /**
     * The configuration entries registry
     * @var array
     */
    private static $__registry;

    /**
     * Some internal configuration entries
     * @var array
     */
    private static $__internals = array(
        'config-class' => null,
        'config-interface' => 'Library\StaticConfiguration\ConfiguratorInterface'
    );

    /**
     * @param string $class_name
     * @return void
     * @throws \InvalidArgumentException if no `$class_name` is defined for the first call
     * @throws \DomainException if class `$class_name` doesn't implement the ConfiguratorInterface
     * @throws \DomainException if class `$class_name` doesn't exist
     */
    public static function load($class_name = null)
    {
        if (empty(self::$__registry) && empty(self::$__configurator) && empty($class_name)) {
            $class_name = self::getInternal('config-class');
            if (empty($class_name)) {
                throw new \InvalidArgumentException(
                    sprintf('Configurator class name is required to instanciante "%s"', __CLASS__)
                );
            }
        }
        if (empty($class_name)) $class_name = get_class(self::$__configurator);

        // init the registry
        if (empty(self::$__registry)) {
            self::setRegistry(array_combine(
                $class_name::getRequired(),
                array_pad(array(), count($class_name::getRequired()), null)
            ));
        }

        // init the configurator object
        if (empty(self::$__configurator) || $class_name!=get_class(self::$__configurator)) {
            if (class_exists($class_name)) {
                $interfaces = class_implements($class_name);
                $config_interface = self::getInternal('config-interface');
                if (in_array($config_interface, $interfaces)) {
                    self::setConfigurator(new $class_name);
                } else {
                    throw new \DomainException(
                        sprintf('Configuration class "%s" must implements interface "%s"!',
                            $class_name, $config_interface)
                    );
                }
            } else {
                throw new \DomainException(
                    sprintf('Configuration class "%s" not found!', $class_name)
                );
            }
        }
    }

    /**
     * Check if the configurator is loaded
     * @return bool
     */
    public static function loaded()
    {
        return !empty(self::$__configurator);
    }
    
    /**
     * @param \Library\StaticConfiguration\ConfiguratorInterface $object
     * @return void
     * @throws \Exception if the config class do not define all required values
     */
    public static function setConfigurator(ConfiguratorInterface $object)
    {
        self::$__configurator = $object;
        $defaults = self::$__configurator->getDefaults();
        if (self::validate($defaults)) {
            self::setRegistry($defaults);
        } else {
            throw new \Exception(
                sprintf('Configuration class "%s" do not define all required values!', 
                    $class_name)
            );
        }
    }

    /**
     * @return array
     */
    public static function getConfigurator()
    {
        return self::$__configurator;
    }

    /**
     * @param array
     * @return void
     */
    public static function setRegistry(array $registry)
    {
        self::$__registry = $registry;
    }

    /**
     * @return array
     */
    public static function getRegistry()
    {
        return self::$__registry;
    }

    /**
     * @return array
     */
    public static function getDefaults()
    {
        return self::$__configurator->getDefaults();
    }

    /**
     * @return array
     */
    public static function getRequired()
    {
        return self::$__configurator->getRequired();
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getInternal($name)
    {
        $configs = self::$__internals;
        return isset($configs[$name]) ? (
            is_string($configs[$name]) ? trim($configs[$name]) : $configs[$name]
        ) : null;
    }

    /**
     * Check if a custom Config class defines all required values
     * @param array $entries
     * @return bool
     */
    public static function validate(array $entries)
    {
        if (!self::loaded()) return false;
        $base = self::$__configurator->getRequired();
        foreach ($entries as $var=>$val) {
            if (in_array($var, $base)) {
                unset($base[array_search($var, $base)]);
            }
        }
        return (count($base)===0);
    }

    /**
     * Overload a config registry
     *
     * @param   array   $settings
     * @return  void
     */
    public static function overload(array $settings)
    {
        if (!self::loaded()) return false;
        foreach ($settings as $var=>$val) {
            self::set($var, $val);
        }
    }
    
    /**
     * @param   string  $name
     * @param   mixed   $value
     * @return  void
     */
    public static function set($name, $value)
    {
        if (!self::loaded()) return false;
        if (array_key_exists($name, self::$__registry)) {
            self::$__registry[$name] = $value;
        }
    }

    /**
     * @param   string  $name
     * @param   mixed   $default
     * @return  mixed
     */
    public static function get($name, $default = null)
    {
        if (!self::loaded()) return false;
        return isset(self::$__registry[$name]) ? (
            is_string(self::$__registry[$name]) ?
                trim(self::$__registry[$name]) : self::$__registry[$name]
        ) : $default;
    }

    /**
     * @param   string $name
     * @return  mixed
     */
    public static function getDefault($name)
    {
        if (!self::loaded()) return false;
        $configs = self::$__configurator->getDefaults();
        return isset($configs[$name]) ? (
            is_string($configs[$name]) ? trim($configs[$name]) : $configs[$name]
        ) : null;
    }

}

