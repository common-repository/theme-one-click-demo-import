<?php

/**
 * Handles the UI generation part
 * 
 * @since       1.0.0
 * @package     Theme404_Once_Click_Demo_Import
 * @subpackage  Theme404_Once_Click_Demo_Import/Inc/Core/UI
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class Theme404_OCDI_Admin
 * 
 * Handles the creation of admin page and its user interfaces.
 */
class Theme404_OCDI_Admin
{
    /**
     * Single class instance.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var object
     */
    private static $instance = null;

    /**
     * Page slug.
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @var string
     */
    protected $page = '';

    /**
     * Creates the Admin page and handles importer UI stuffs.
     *
     * @class Theme404_OCDI_Admin
     * 
     * @version 1.0.0
     * @since 1.0.0
     * 
     * @return object Theme404_OCDI_Admin
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Theme404_OCDI_Admin::getInstance()
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
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'theme404-one-click-demo-import'),
            '1.0.0'
        );
    }

    /**
     * You cannot unserialize instances of this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'theme404-one-click-demo-import'),
            '1.0.0'
        );
    }

    /**
     * Initialize the Theme404_OCDI_Admin
     */
    private function init()
    {
        add_action('admin_menu', [$this, 'createAdminMenu']);
        add_action('admin_footer', [$this, 'renderTemplates']);

        add_action('admin_init', [$this, 'onAdminInit']);
    }

    /**
     * When admin init runs.
     */
    public function onAdminInit()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_GET['_clear']) && 'cache' === $_GET['_clear']) {
            $this->deleteCache();
        }
    }

    /**
     * Delete the cache.
     */
    private function deleteCache()
    {
        global $wpdb;
        $table = $wpdb->options;
        $query = "SELECT * FROM {$table} WHERE `option_name` LIKE '%_theme404_ocdi_%'";
        $results = $wpdb->get_results($query);

        if (!$results) {
            return;
        }

        if (is_array($results) && count($results) > 0) {
            foreach ($results as $result) {
                $wpdb->delete($table, ['option_id' => $result->option_id]);
            }
        }
    }

    /**
     * Hooked into 'admin_menu' to register the main page.
     */
    public function createAdminMenu()
    {
        $args = theme404_ocdi()->getAdminPageArgs();

        if ('submenu' === $args['menu_type']) {

            $page = add_submenu_page(
                $args['parent'],
                $args['title'],
                $args['menu_name'],
                'manage_options',
                $args['slug'],
                [$this, 'renderAdminPage'],
                $args['position']
            );
        } else if ('menu' === $args['menu_type']) {

            $page = add_menu_page(
                $args['title'],
                $args['menu_name'],
                'manage_options',
                $args['slug'],
                [$this, 'renderAdminPage'],
                $args['icon'],
                $args['position']
            );
        }

        $this->page = $page;

        add_action('admin_enqueue_scripts', [$this, 'enqueueScriptsStyles']);
    }


    /**
     * Render the admin page.
     * 
     * @since 1.0.0
     * @return void.
     */
    public function renderAdminPage()
    {
        $data = [
            'categories' => theme404_ocdi()->api()->categories(),
            'demos' => theme404_ocdi()->api()->demos()
        ];

        theme404_ocdi()->view('body', $data);
    }

    /**
     * Enqueue styles and scripts.
     * 
     * @since 1.0.0
     * @return void
     */
    public function enqueueScriptsStyles($hook)
    {
        $slug = theme404_ocdi()->getSlug();

        if ($this->page === $hook) {

            // Enqueue styles.
            wp_enqueue_style(
                'sweetalert',
                THEME404_OCDI_CSS . 'sweetalert.css',
                [],
                THEME404_OCDI_VERSION,
                'all'
            );

            wp_enqueue_style(
                $slug,
                THEME404_OCDI_CSS . 'admin.css',
                [],
                THEME404_OCDI_VERSION,
                'all'
            );

            // Enqueue scripts.
            wp_enqueue_script(
                "sweetalert",
                THEME404_OCDI_JS . 'sweetalert.js',
                [],
                THEME404_OCDI_VERSION,
                true
            );

            wp_register_script(
                $slug,
                THEME404_OCDI_JS . 'admin-ui.js',
                ['jquery', 'wp-util', 'updates'],
                THEME404_OCDI_VERSION,
                true
            );

            $theme       =  get_stylesheet();
            $licenseSlug = "{$theme}-license";
            $licenseUrl  = admin_url("themes.php?page={$licenseSlug}");

            // Localize strings.
            $default = [
                'nonce'          => wp_create_nonce(),
                'themeName'      => theme404_ocdi_get_theme_name(),
                'offlineTitle'   => esc_html__('You\'re Offline!', 'theme404-one-click-demo-import'),
                'purchaseLabel'  => esc_html__('Purchase Now', 'theme404-one-click-demo-import'),
                'previewLabel'   => esc_html__('Preview', 'theme404-one-click-demo-import'),
                'loadingText'    => esc_html__('Please Wait!', 'theme404-one-click-demo-import'),
                'installPlugins' => esc_html__('Install Plugins', 'theme404-one-click-demo-import'),
                'importContent'  => esc_html__('Import Content', 'theme404-one-click-demo-import'),
                'installing'     => esc_html__('Installing &#8230;', 'theme404-one-click-demo-import'),
                'activating'     => esc_html__('Activating &#8230;', 'theme404-one-click-demo-import'),
                'active'         => esc_html__('Active', 'theme404-one-click-demo-import'),
                'failedTitle'    => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'activateLink'   => esc_url($licenseUrl),
                'offlineMsg'     => esc_html__(
                    'We cannot import now. Please try again later!',
                    'theme404-one-click-demo-import'
                ),
                'tryAgain'       => esc_html__(
                    'Refresh the page, and try again!',
                    'theme404-one-click-demo-import'
                ),
                'content'        => esc_html__(
                    'Importing Content&#8230;',
                    'theme404-one-click-demo-import'
                ),
                'customizer'     => esc_html__(
                    'Importing Customize Information &#8230;',
                    'theme404-one-click-demo-import'
                ),
                'widgets'        => esc_html__(
                    'Importing Widgets &#8230;',
                    'theme404-one-click-demo-import'
                ),
                'slider'         => esc_html__(
                    'Importing Slider &#8230;',
                    'theme404-one-click-demo-import'
                ),
                'failed'         => esc_html__(
                    'Something Went Wrong!',
                    'theme404-one-click-demo-import'
                ),
                'prepare'        => esc_html__(
                    'Preparing to import &#8230;',
                    'theme404-one-click-demo-import'
                ),
                'menu'           => esc_html__(
                    'Setting Menus &#8230;',
                    'theme404-one-click-demo-import'
                ),
                'pages'          => esc_html__(
                    'Setting Pages &#8230;',
                    'theme404-one-click-demo-import'
                ),
                'finalize'       => esc_html__(
                    'Finalizing the Import &#8230;',
                    'theme404-one-click-demo-import'
                ),
            ];

            $user_args = apply_filters('theme404_ocdi_localize_data', []);
            $args      = wp_parse_args($user_args, $default);

            wp_localize_script($slug, 'theme404ocdiData', $args);
            wp_enqueue_script($slug);
        }
    }

    /**
     * Render popup templates.
     * 
     * @since 1.0.0
     * @return void
     */
    public function renderTemplates()
    {
        $currentScreen = get_current_screen();

        if ($this->page === $currentScreen->id) {
            theme404_ocdi()->view('popups/activate-theme');
            theme404_ocdi()->view('popups/failed');
            theme404_ocdi()->view('popups/purchase-theme');
            theme404_ocdi()->view('popups/information');
            theme404_ocdi()->view('import');
            theme404_ocdi()->view('importing');
            theme404_ocdi()->view('complete');
        }
    }
}
