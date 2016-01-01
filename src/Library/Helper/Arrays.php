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
 * Array helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Arrays as ArrayHelper;
 *
 * @author  piwi <me@e-piwi.fr>
 */
class Arrays
{

    /**
     * Safely [sort](http://php.net/sort) an array
     *
     * @param $array
     * @param int $sort_flag
     * @return array
     */
    public static function sort($array, $sort_flag = SORT_STRING)
    {
        if (!is_array($array)) {
            return array();
        }
        sort($array, $sort_flag);
        return $array;
    }

    /**
     * Safely [ksort](http://php.net/ksort) an array
     *
     * @param $array
     * @param int $sort_flag
     * @return array
     */
    public static function ksort($array, $sort_flag = SORT_STRING)
    {
        if (!is_array($array)) {
            return array();
        }
        ksort($array, $sort_flag);
        return $array;
    }

    /**
     * Safely [natsort](http://php.net/natsort) an array
     *
     * @param $array
     * @return array
     */
    public static function natsort($array)
    {
        if (!is_array($array)) {
            return $array;
        }
        natsort($array);
        return $array;
    }

    /**
     * Safely [natcasesort](http://php.net/natcasesort) an array
     *
     * @param $array
     * @return array
     */
    public static function natcasesort($array)
    {
        if (!is_array($array)) {
            return $array;
        }
        natcasesort($array);
        return $array;
    }

}

