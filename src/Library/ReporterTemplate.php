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
 * @author      Pierre Cassat & contributors <piero.wbmstr@gmail.com>
 */
class ReporterTemplate
{

    /**
     * Building of a view content including a view file passing it parameters
     *
     * The view file will be included "as-is" so:
     *
     * - it may exists,
     * - it can be either some full HTML, some CSS, some JS containing PHP scripts
     *
     * The parameters will be merged with the object `$default_view_params` and exported
     * in the global context of the view file. For example, if you define a parameter named
     * `param` on a certain value, writing ths following in your view file :
     *
     *     <?php echo $param; ?>
     *
     * will render the value.
     *
     * The best practice is to NOT use the small php tags `<?= ... ?>`.
     *
     * @param string $view The view filename (which must exist)
     * @param array $params An array of the parameters passed for the view parsing
     * @throw Throws an RuntimeException if the file view can't be found
     * @return string Returns the view file content rendering
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
     * The table of the default parameters loaded in each view
     */
    protected $default_view_params = array();

    /**
     * Set an array of the default parameters for all views
     *
     * @param array $params The array of default parameters
     * @return self Returns `$this` for method chaining
     */
    public function setDefaultViewParams(array $params)
    {
        $this->default_view_params = $params;
        return $this;
    }

    /**
     * Get the default parameters for all views
     *
     * @return array The array of default parameters
     */
    public function getDefaultViewParams()
    {
        return $this->default_view_params;
    }

    /**
     * Get a value of the default parameters for all views
     *
     * @parameter string $name The name of the parameter to get
     * @parameter misc $default The default value returns if no parameter is defined for `$name`
     * @return misc The parameter value if found, `$default` otherwise
     */
    public function getDefaultViewParam($name, $default = null)
    {
        return isset($this->default_view_params[$name]) ? $this->default_view_params[$name] : $default;
    }

}

// Endfile
