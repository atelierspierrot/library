<?php
/**
 * This file is part of the Library package.
 *
 * Copyright (c) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
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


namespace Library\StaticConfiguration;

/**
 * Config class interface to use with \Library\StaticConfiguration\Config
 *
 * @author  piwi <me@e-piwi.fr>
 */
interface ConfiguratorInterface
{

    /**
     * Get the default configuration values
     *
     * This must define at least the requires entries defined below.
     * During the configuration object lifecycle, no other entry than these defaults
     * could be define.
     *
     * @return array
     */
    public static function getDefaults();

    /**
     * Get the required configuration entries
     *
     * @return array
     */
    public static function getRequired();

}

// Endfile