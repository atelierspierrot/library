<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library;

/**
 * @author 		Pierre Cassat & contributors <piero.wbmstr@gmail.com>
 */
class ReporterTemplate
{

    /**
     * Building of a view content including a view file passing it parameters
     *
     * @param string $view The view filename
     * @param array $params An array of the parameters passed for the view parsing
     * @throw Throws an DocBookRuntimeException if the file view can't be found
     */
    public function view($view, array $params = array())
    {
        $params =  array_merge($this->getDefaultViewParams(), $params);
        if ($view && @file_exists($view)) {
            if (!empty($params))
                extract($params, EXTR_OVERWRITE);
            ob_start();
            include $view;
            $output = ob_get_contents();
            ob_end_clean();
        } else {
            throw new \RuntimeException(
                sprintf('Template "%s" can\'t be found!', $view)
            );
        }
        return $output;
    }

    /**
     * Get an array of the default parameters for all views
     */
    public function getDefaultViewParams()
    {
        return array(
        );
    }

}

// Endfile