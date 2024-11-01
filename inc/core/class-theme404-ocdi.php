<?php

/**
 * The methods defined here is run during this plugin activation.
 * 
 * @since       1.0.0
 * @package     Theme404_Once_Click_Demo_Import
 * @subpackage  Theme404_Once_Click_Demo_Import/Inc/Core
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class Theme404_OCDI
 * 
 * Core file of the plugin.
 */

final class Theme404_OCDI
{
    /**
     * The single class instance.
     *
     * @since 1.0.0
     * @access private
     *
     * @var object
     */
    private static $instance = null;

    /**
     * API Url for demo data.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var string
     */
    private $apiUrl = '';

    /**
     * Admin page arugments.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var string
     */
    private $adminPageArgs = [];

    /**
     * Should display admin page?
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var boolean
     */
    private $displayPanel = true;

    /**
     * Main Theme404_OCDI Instance
     *
     * Ensures only one instance of this class exists in memory at any one time.
     *
     * @see Theme404_OCDI()
     * @uses Theme404_OCDI::init_globals() Setup class globals.
     * @uses Theme404_OCDI::init_includes() Include required files.
     * @uses Theme404_OCDI::init_actions() Setup hooks and actions.
     *
     * @since 1.0.0
     * @static
     * @return Theme404_OCDI.
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();

            self::$instance->initGlobals();
            self::$instance->includeCoreFiles();
            self::$instance->runActions();
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Theme404_OCDI::getInstance()
     *
     * @since 1.0.0
     * @access private
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        /* We do nothing here! */
    }

    /**
     * You cannot clone this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'theme404-one-click-demo-import'), '1.0.0');
    }

    /**
     * You cannot unserialize instances of this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'theme404-one-click-demo-import'), '1.0.0');
    }

    /**
     * Initialize the plugin globals.
     */
    private function initGlobals()
    {

        $default = THEME404_OCDI_API_URL;

        $url  = apply_filters('theme404_ocdi_api_url', $default);

        $args = apply_filters('theme404_ocdi_admin_page_args', [
            'menu_type' => 'menu',   // menu | submenu,
            'slug'      => 'theme404-ocdi',
            'menu_name' => esc_html__('Import Demo', 'theme404-one-click-demo-import'),
            'title'     => esc_html__('Import Demo', 'theme404-one-click-demo-import'),
            'icon'      => false,
            'parent'    => false,
            'position'  => 90,
        ]);



        $this->apiUrl = $url;

        $this->adminPageArgs = $args;
    }

    /**
     * Loads our all the core files.
     */
    private function includeCoreFiles()
    {
        /* Include core classes */
        require_once THEME404_OCDI_CLASSES . 'class-theme404-ocdi-api.php';

        require_once THEME404_OCDI_CLASSES . 'class-theme404-ocdi-ajax.php';

        require_once THEME404_OCDI_CLASSES . 'class-theme404-ocdi-plugins.php';
        require_once THEME404_OCDI_CLASSES . 'class-theme404-ocdi-core.php';

        /* Include admin ui */
        require_once THEME404_OCDI_UI . 'class-theme404-ocdi-admin.php';
    }

    /**
     * Fires the actions & filters.
     * 
     * @since 1.0.0
     * @return void
     */
    private function runActions()
    {
        // Load the textdomain.
        add_action('init', [$this, 'loadTextdomain']);
        add_action('plugins_loaded', [$this, 'pluginsLoaded']);

        $this->ajax();
    }

    /**
     * Make plugin available for translation.
     * 
     * @return void
     */
    public function loadTextdomain()
    {
        load_plugin_textdomain('theme404-one-click-demo-import', false, THEME404_OCDI_LANGUAGES);
    }

    /**
     * Runs during the plugin load.
     */
    public function pluginsLoaded()
    {
        $activeTheme = theme404_ocdi_get_theme();

        $authorThemes = get_site_transient('theme404_ocdi_author_themes');

        if (!$authorThemes) {
            $authorThemes = $this->api()->themes();
        }

        if (
            !in_array($activeTheme, $authorThemes) &&
            THEME404_OCDI_AUTHOR !== theme404_ocdi_get_theme_author()
        ) {
            add_action('admin_notices', [$this, 'theme404_ocdi_print_admin_notice']);
        } else {
            $this->admin();
        }

        // For testing purpose.
        // $this->admin();
    }

    /**
     * Displays the admin notice.
     */
    public function theme404_ocdi_print_admin_notice()
    {
        $class = 'notice notice-error is-dismissible';

        echo sprintf('<div class="%s">', $class);
        echo sprintf(
            __('<p>You need to have one of the themes from <a href="%1$s" target="_blank">%2$s</a> installed, to use <strong>%3$s</strong> plugin</p>', 'theme404-one-click-import'),
            esc_url(THEME404_OCDI_AUTHOR_URI),
            ucfirst(THEME404_OCDI_AUTHOR),
            THEME404_OCDI_PLUGIN_NAME
        );
        echo '</div>';
    }

    /**
     * Includes the file.
     * 
     * Generally used for view generation along with data.
     *
     * @since 1.0.0
     * @return void
     */
    public function view($view, $data = [])
    {
        try {
            include THEME404_OCDI_VIEWS . "{$view}.php";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Theme404 API class.
     * 
     * @since 1.0.0
     * @return object Theme404_OCDI_API
     */
    public function api()
    {
        return Theme404_OCDI_API::getInstance();
    }

    /**
     * Theme404 Helper Class
     */
    public function plugins()
    {
        return Theme404_OCDI_Plugins::getInstance();
    }


    /**
     * Theme404 AJAX class.
     * 
     * @since 1.0.0
     * @return void
     */
    public function ajax()
    {
        Theme404_OCDI_Ajax::getInstance();
    }

    /**
     * Generates the Admin pages and UI for the importer
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function admin()
    {
        Theme404_OCDI_Admin::getInstance();
    }

    /**
     * Get the api URL.
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Get the admin page url.
     * 
     * @return string
     */
    public function getPageUrl()
    {
        $args = $this->adminPageArgs;

        $menuType = $args['menu_type'];
        $slug     = $args['slug'];
        $parent   = 'admin.php';

        if ('submenu' == $menuType) {
            $parent = sanitize_text_field($args['parent']);
        }

        $url = admin_url($parent);

        return add_query_arg(['page' => sanitize_key($slug)], $url);
    }

    /**
     * Get thee admin menu slug.
     * 
     * @return array
     */
    public function getAdminPageArgs()
    {
        return $this->adminPageArgs;
    }

    /**
     * Get the admin page slug.
     * 
     * @return string
     */
    public function getSlug()
    {
        return ($this->adminPageArgs)['slug'];
    }


    /**
     * Main Content Importer.
     * 
     * @return Theme404_OCDI_Core
     */
    public function importer()
    {
        return Theme404_OCDI_Core::getInstance();
    }

    /**
     * Get the cached demo from transient.
     * 
     * @return mixed.
     */
    public function demo($slug)
    {
        if (!$slug) {
            return false;
        }

        $theme = theme404_ocdi_get_theme();
        $key = "theme404_ocdi_{$theme}_demo_{$slug}";

        $transientData = get_site_transient($key);

        if (!$transientData) {
            return false;
        }

        return $transientData['data'];
    }
}
