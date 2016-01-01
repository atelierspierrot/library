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


namespace Library\CommandLine;

use \InvalidArgumentException;
use \Library\CodeParser;
use \Library\CommandLine\Formater;
use \Library\CommandLine\Stream;

/**
 * @author  piwi <me@e-piwi.fr>
 */
class Helper
{

    public static function getopt(array $options)
    {
        $short_options = implode('', array_keys($options['argv_options']));
        $long_options = array_keys($options['argv_long_options']);
        if (!empty($options['commands'])) {
            foreach (array_keys($options['commands']) as $_cmd) {
                array_push($long_options, $_cmd);
            }
        }
        if (!empty($options['aliases'])) {
            foreach (array_keys($options['aliases']) as $_ali) {
                array_push($long_options, $_ali);
            }
        }
        return getopt($short_options, $long_options);
    }

    public static function getOptionMethod(array $options, $arg = null)
    {
        if (empty($arg)) {
            return null;
        }
        foreach (array($arg, $arg.':', $arg.'::') as $_arg) {
            if (array_key_exists($_arg, $options['argv_options'])) {
                return $options['argv_options'][$_arg];
            } elseif (array_key_exists($_arg, $options['argv_long_options'])) {
                return $options['argv_long_options'][$_arg];
            }
        }
        return null;
    }

    public static function getOptionDescription($arg, $caller)
    {
        if (!is_object($caller) || !($caller instanceof CommandLineControllerInterface)) {
            throw new InvalidArgumentException(
                sprintf('Argument 2 for method "%s::getOptionDescription" must be an object and implement the "CommandLineControllerInterface" interface!', __CLASS__)
            );
        }
        $_meth_descr = 'get'.ucfirst($arg).'Description';
        if (method_exists($caller, $_meth_descr)) {
            $ctt = $caller->$_meth_descr();
        } else {
            $code_parser = new CodeParser(get_class($caller).':run'.ucfirst($arg).'Command', CodeParser::PARSE_METHOD);
            if ($str = $code_parser->get_shortDescription()) {
                $ctt = $str;
            } else {
                $ctt = $_meth;
            }
        }
        return $ctt;
    }

    public static function treatOptions(array $options, $caller)
    {
        if (!is_object($caller) || !($caller instanceof CommandLineControllerInterface)) {
            throw new InvalidArgumentException(
                sprintf('Argument 2 for method "%s::treatOptions" must be an object and implement the "CommandLineControllerInterface" interface!', __CLASS__)
            );
        }
        $params = self::getopt($options);
        foreach ($params as $_opt_name=>$_opt_val) {
            $_meth=null;
            if (array_key_exists($_opt_name, $options['argv_options'])) {
                $_meth = $options['argv_options'][$_opt_name];
            } elseif (array_key_exists($_opt_name, $options['argv_long_options'])) {
                $_meth = $options['argv_long_options'][$_opt_name];
            }

            if (!empty($_meth)) {
                $_cls_meth = 'run'.ucfirst($_meth).'Command';
                if (method_exists($caller, $_cls_meth) and !in_array($_cls_meth, $caller->getDoneMethods())) {
                    $caller->addDoneMethod($_cls_meth);
                    $caller->$_cls_meth($_opt_val);
                }
            }
        }
    }

    public static function getOptionHelp($arg, $caller)
    {
        if (!is_object($caller) || !($caller instanceof CommandLineControllerInterface)) {
            throw new InvalidArgumentException(
                sprintf('Argument 2 for method "%s::getOptionHelp" must be an object and implement the "CommandLineControllerInterface" interface!', __CLASS__)
            );
        }
        $_meth_descr = 'get'.ucfirst($arg).'Help';
        if (method_exists($caller, $_meth_descr)) {
            $ctt = $caller->$_meth_descr();
        } else {
            $code_parser = new CodeParser(get_class($caller).':run'.ucfirst($arg).'Command', CodeParser::PARSE_METHOD);
            if ($str = $code_parser->get_longDescription()) {
                $ctt = $str;
            } else {
                $ctt = $_meth;
            }
        }
        return $ctt;
    }

    /**
     */
    public static function getHelpInfo(array $options = array(), Formater $formater, $caller)
    {
        if (!is_object($caller) || !($caller instanceof CommandLineControllerInterface)) {
            throw new InvalidArgumentException(
                sprintf('Argument 3 for method "%s::getHelpInfo" must be an object and implement the "CommandLineControllerInterface" interface!', __CLASS__)
            );
        }
        $tmp_options_list = $tmp_commands_list = $tmp_alias_list = array();
        foreach ($options['argv_options'] as $_optn=>$_methodn) {
            if (in_array($_methodn, $options['commands'])) {
                if (!isset($tmp_commands_list[$_methodn])) {
                    $tmp_commands_list[$_methodn] = array();
                }
                $tmp_commands_list[$_methodn][] = '-'.$_optn;
            } elseif (in_array($_methodn, $options['aliases'])) {
                if (!isset($tmp_alias_list[$_methodn])) {
                    $tmp_alias_list[$_methodn] = array();
                }
                $tmp_alias_list[$_methodn][] = '-'.$_optn;
            } else {
                if (!isset($tmp_options_list[$_methodn])) {
                    $tmp_options_list[$_methodn] = array();
                }
                $tmp_options_list[$_methodn][] = '-'.$_optn;
            }
        }
        foreach ($options['argv_long_options'] as $_loptn=>$_lmethodn) {
            if (!isset($tmp_options_list[$_lmethodn])) {
                $tmp_options_list[$_lmethodn] = array();
            }
            $tmp_options_list[$_lmethodn][] = '--'.$_loptn;
        }

        $options_list = array();
        foreach ($tmp_options_list as $_meth=>$_options) {
            $title = str_replace(':', '', implode('|', $_options));
            if (substr_count(implode('|', $_options), '::')) {
                $title .= ' <opt.arg>';
            } elseif (substr_count(implode('|', $_options), ':')) {
                $title .= ' <arg>';
            }
            $options_list[$title] = self::getOptionDescription($_meth, $caller);
        }

        foreach ($options['commands'] as $_optn=>$_methodn) {
            if (!isset($tmp_commands_list[$_methodn])) {
                $tmp_commands_list[$_methodn] = array();
            }
            $tmp_commands_list[$_methodn][] = $_optn;
        }

        $commands_list = array();
        foreach ($tmp_commands_list as $_meth=>$_options) {
            $title = str_replace(':', '', implode('|', $_options));
            if (substr_count(implode('|', $_options), '::')) {
                $title .= ' <opt.arg>';
            } elseif (substr_count(implode('|', $_options), ':')) {
                $title .= ' <arg>';
            }
            $commands_list[$title] = self::getOptionDescription($_meth, $caller);
        }

        foreach ($options['aliases'] as $_optn=>$_methodn) {
            if (!isset($tmp_alias_list[$_methodn])) {
                $tmp_alias_list[$_methodn] = array();
            }
            $tmp_alias_list[$_methodn][] = $_optn;
        }

        $alias_list = array();
        foreach ($tmp_alias_list as $_meth=>$_options) {
            $title = str_replace(':', '', implode('|', $_options));
            if (substr_count(implode('|', $_options), '::')) {
                $title .= ' <opt.arg>';
            } elseif (substr_count(implode('|', $_options), ':')) {
                $title .= ' <arg>';
            }
            $alias_list[$title] = self::getOptionDescription($_meth, $caller);
        }

        $help_str = self::formatHelpString(
            $caller->getVersionString(),
            array(
                'Usage'=>"
    \$ php {$caller->getScript()} -[<var>options</var>] <var>command</var> [<var>arguments</var>]
                ",
                'List of available options'=>$options_list,
                'List of available commands'=>$commands_list,
                'List of available aliases'=>$alias_list,
                'Notes'=>'The equal sign is required if the argument is optional : <var>-e=all</var> / <var>--env=all</var>.
It is optional if the argument is required : <var>-e all</var> / <var>--env all</var>.
You can group one letter options in a unique string (option with arguments may be last) : <var>-xhe=all</var>.
You can write as many options you like : <var>-x --env=all</var>.',
            ),
            $formater
        );
        return $help_str;
    }

    public static function formatHelpString($title = null, $contents = null, Formater $formater)
    {
        if (empty($contents)) {
            return '';
        }
        if (empty($title)) {
            $title = 'Help';
        }
        if (!is_array($contents)) {
            $contents = array( $contents );
        }

        $help_ctt='';
        foreach ($contents as $ctt_ttl=>$ctt_ctt) {
            if (!empty($ctt_ctt)) {
                if (!empty($ctt_ttl)) {
                    $help_ctt .= "<bold>{$ctt_ttl}:</bold>".Formater::$nl;
                }
                if (is_string($ctt_ctt)) {
                    $help_ctt .= $ctt_ctt.Formater::$nl;
                } else {
                    $ctt_str='';

                    // les cles sont des chaines ?
                    $keys_are_strings = false;
                    $_strs = array_keys($ctt_ctt);
                    for ($i=0;$i<count($_strs);$i++) {
                        if (is_string($_strs[$i])) {
                            $keys_are_strings = true;
                        }
                    }

                    // taille max si chaines
                    if ($keys_are_strings === true) {
                        $maxlength=0;
                        foreach ($_strs as $_ttl) {
                            if (strlen($_ttl)>$maxlength) {
                                $maxlength=strlen($_ttl);
                            }
                        }
                        foreach ($ctt_ctt as $opt_ttl=>$opt_ctt) {
                            if (is_array($opt_ctt)) {
                                for ($_k=0;$_k<count($opt_ctt);$_k++) {
                                    if ($_k==0) {
                                        $ctt_str .= '  <list_title>'.str_pad($opt_ttl, $maxlength).'</list_title>  '.$opt_ctt[$_k].Formater::$nl;
                                    } else {
                                        $ctt_str .= '  '.str_pad('', $maxlength).'  '.$opt_ctt[$_k].Formater::$nl;
                                    }
                                }
                            } else {
                                $ctt_str .= '  <list_title>'.str_pad($opt_ttl, $maxlength).'</list_title>  '.$opt_ctt.Formater::$nl;
                            }
                        }
                    } else {
                        foreach ($ctt_ctt as $opt_ttl=>$opt_ctt) {
                            $ctt_str .= '  '.$opt_ctt.Formater::$nl;
                        }
                    }
                    $help_ctt .= $ctt_str.Formater::$nl;
                }
            }
        }

        $help_str = <<<EOT

<info>{$title} help</info>

{$help_ctt}
EOT;
        return $help_str;
    }
}
