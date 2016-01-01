<?php
/**
 * This file is part of the Library package.
 *
 * Copyright (c) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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


namespace Library\Helper;

/**
 * Backtrace helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Backtrace as BacktraceHelper;
 *
 * @author  piwi <me@e-piwi.fr>
 */
class Backtrace
{

    /**
     * Returns current PHP backtrace
     *
     * @param   int $strip_indexes  Number of entries to strip
     * @return  array
     */
    public static function getBacktrace($strip_indexes = 0)
    {
        $traces = debug_backtrace();
        if ($strip_indexes>0 && $strip_indexes<count($traces)) {
            $traces = array_slice($traces, $strip_indexes);
        }
        return $traces;
    }

    /**
     * Returns a full backtrace set by reverse-index
     *
     * @param   int     $index              The index of the trace to get in the backtrace pile
     * @param   string  $reflection_class   The name of a trace reflection class to return
     * @return  null|array|object           The stack trace entry if found or NULL
     */
    public static function getTrace($index = 1, $reflection_class = null)
    {
        $traces = self::getBacktrace();
        $trace  = array_key_exists($index, $traces) ? $traces[ $index ] : null;
        if (!is_null($reflection_class) && class_exists($reflection_class)) {
            return new $reflection_class($trace);
        }
        return $trace;
    }

    /**
     * Returns a backtrace information by reverse-index
     *
     * Type can be :
     *    - 'func', 'function'
     *    - 'line'
     *    - 'file', 'filename'
     *    - 'class'
     *    - 'obj', 'object'
     *    - 'type'
     *    - 'args', 'arguments'
     *    - 'arg', 'argument' : requires to define the $arg_index parameter
     *
     * By default, the function will return a whole trace set.
     *
     * @param   int     $index      The index of the trace to get in the backtrace pile
     * @param   string  $type       The type of the trace entry to get
     * @param   int     $arg_index  The argument index to get only an argument value
     * @return  mixed   The value found or NULL
     */
    public static function getTraceInfo($index = 1, $type = null, $arg_index = null)
    {
        $trace = self::getTrace($index + 1);
        if (!is_null($trace)) {
            if (!is_null($type)) {
                switch ($type) {
                    case 'func': case 'function' :
                        return isset($trace['function']) ? $trace['function'] : null;
                        break;
                    case 'file': case 'filename':
                        return isset($trace['file']) ? $trace['file'] : null;
                        break;
                    case 'line':
                        return isset($trace['line']) ? $trace['line'] : null;
                        break;
                    case 'class':
                        return isset($trace['class']) ? $trace['class'] : null;
                        break;
                    case 'obj': case 'object':
                        return isset($trace['object']) ? $trace['object'] : null;
                        break;
                    case 'type':
                        return isset($trace['type']) ? $trace['type'] : null;
                        break;
                    case 'args': case 'arguments':
                        return isset($trace['args']) ? $trace['args'] : null;
                        break;
                    case 'arg': case 'argument':
                        if (!is_null($arg_index)) {
                            return isset($trace['args']) && isset($trace['args'][$arg_index]) ? $trace['args'][$arg_index] : null;
                        }
                        break;
                    default: return null; break;
                }
            }
            return $trace;
        }
        return null;
    }

    /**
     * Get a trace `file` entry (by index, or last one by default)
     *
     * @param int $index
     * @return mixed
     */
    public static function callingFile($index = 1)
    {
        return self::getTraceInfo(($index + 1), 'file');
    }

    /**
     * Get a trace `line` entry (by index, or last one by default)
     *
     * @param int $index
     * @return mixed
     */
    public static function callingLine($index = 1)
    {
        return self::getTraceInfo(($index + 1), 'line');
    }

    /**
     * Get a trace `function` entry (by index, or last one by default)
     *
     * @param int $index
     * @return mixed
     */
    public static function callingFunction($index = 1)
    {
        return self::getTraceInfo(($index + 1), 'function');
    }

    /**
     * Get a trace `class` entry (by index, or last one by default)
     *
     * @param int $index
     * @return mixed
     */
    public static function callingClass($index = 1)
    {
        return self::getTraceInfo(($index + 1), 'class');
    }

}

