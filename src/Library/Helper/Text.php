<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Helper;

/**
 * Text helper
 *
 * As for all helpers, all methods are statics.
 *
 * @author      Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Text
{

    /**
     * Fonction qui tronque un texte en fonction d'une longueur specifiee, et lui ajoute ou non '...'
     *
     * @param string $string La chaîne à couper
     * @param integer $length La longueur voulue, sans compter l'ajout final (par défaut 20)
     * @param string $end_str Chaîne finale à ajouter (par defaut '...')
     * @return string
     */
    public static function cut($string = '', $length = 120, $end_str = ' ...')
    {
        if (empty($string)) return '';
        if (strlen($string) >= $length) {
            $stringint = substr($string, 0, $length);
            $last_space = strrpos($stringint, " ");
            $stringinter = substr($stringint, 0, $last_space).$end_str;
            if (strlen($stringinter) === strlen($end_str)) {
                $stringcut = $stringint.$end_str;
            } else {
                $stringcut = $stringinter;
            }
        } else {
            $stringcut = $string;
        }
        return $stringcut;
    }

}

// Endfile
