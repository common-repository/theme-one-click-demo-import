<?php

/**
 * Handle the AJAX sent through demo importer.
 * 
 * @since       1.0.0
 * @package     Theme404_Once_Click_Demo_Import
 * @subpackage  Theme404_Once_Click_Demo_Import/Inc/Core/UI
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class Theme404_OCDI_Ajax
 * 
 * Handles the AJAX Actions.
 */

class Theme404_OCDI_Ajax
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
     * AJAX Actions.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var array
     */
    private $actions = [];

    /**
     * Registers and fires the AJAX actions.
     *
     * @class Theme404_OCDI_Ajax
     * 
     * @version 1.0.0
     * @since 1.0.0
     * 
     * @return object Theme404_OCDI_Ajax
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->define();
            self::$instance->register();
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Theme404_OCDI_Ajax::getInstance()
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
     * Defines all the AJAX actions.
     */
    private function define()
    {
        $this->actions = [
            'retrieve-demo'        => 'retrieveDemo',
            'list-plugins'         => 'listPlugins',
            'ocdi-install-plugin'  => 'installPlugin',
            'ocdi-activate-plugin' => 'activatePlugin',
            'prepare-import'       => 'prepareImport',
            'content-import'       => 'importContent',
            'customizer-import'     => 'importCustomize',
            'widgets-import'        => 'importWidget',
            'slider-import'        => 'importSlider',
            'menu-import'          => 'importMenu',
            'pages-import'         => 'importPages',
            'finalize-import'      => 'finalize'
        ];
    }

    /**
     * Registers all the AJAX actions.
     */
    private function register()
    {
        foreach ($this->actions as $key => $value) {
            $ajaxAction = "wp_ajax_{$key}";

            add_action($ajaxAction, [$this, $value]);
        }
    }

    /**
     * AJAX Request
     * ----
     * Queries the API to get full demo details.
     * 
     * @uses wp_send_json_success()
     * @uses wp_send_json_error()
     * 
     * @return array
     */
    public function retrieveDemo()
    {
        $request = $_REQUEST;

        $demo     = sanitize_text_field($request['demo']);
        $demoType = sanitize_text_field($request['demoType']);

        $nonceKey = "retrieve-demo-{$demo}";

        $response = [];

        if (!wp_verify_nonce($request['nonce'], $nonceKey)) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'code'    => 'not_allowed',
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($request);
        }

        $result = theme404_ocdi()->api()->demo($demo, $demoType);

        if ($result['success']) {

            // We've retrieved the demo information.
            // Break provided information.
            $resDemo     = $result['data'];
            $information = [];

            $information['name']    = $resDemo['name'];
            $information['slug']    = $resDemo['slug'];
            $information['image']   = $resDemo['image'];
            $information['preview'] = $resDemo['preview'];

            wp_send_json_success(['demo' => $information]);
        } else {
            wp_send_json_error([
                'title' => $result['title'],
                'message' => $result['message'],
            ]);
        }
    }

    /**
     * AJAX Request
     * ----
     * List the plugins used in the demo.
     * 
     * @uses wp_send_json_success()
     * @uses wp_send_json_error()
     * 
     * @return array
     */
    public function listPlugins()
    {
        $request = $_REQUEST;
        $theme   = theme404_ocdi_get_theme();
        $slug    = sanitize_text_field($request['slug']);
        $nonce   = sanitize_text_field($request['nonce']);

        if (!wp_verify_nonce($nonce, 'list-plugins')) {

            // Nonce cannot be verified. Try again later.
            $response = [
                'code'    => 'not_allowed',
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        $demoKey = "theme404_ocdi_{$theme}_demo_{$slug}";

        $result  = get_site_transient($demoKey);

        if ($result) {
            $demo = $result['data'];

            if (isset($demo['plugins'])) {
                $status = theme404_ocdi()->plugins()
                    ->runtime($demo)
                    ->html();

                wp_send_json_success($status);
            }
        }

        // Nonce cannot be verified. Try again later.
        $response = [
            'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
            'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
        ];

        wp_send_json_error($response);
    }

    /**
     * AJAX Request
     * ----
     * Install the plugin.
     * 
     * @uses wp_send_json_success()
     * @uses wp_send_json_error()
     * 
     * @return array
     */
    public function installPlugin()
    {
        $request = $_REQUEST;
        $nonce   = sanitize_text_field($request['nonce']);
        $plugin  = sanitize_text_field($request['slug']);

        $nonceKey = "install-{$plugin}";

        if (!wp_verify_nonce($nonce, $nonceKey)) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'code'    => 'not_allowed',
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        $result = theme404_ocdi()->plugins()->ajaxInstall($plugin);

        if (isset($result['success']) && !$result['success']) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'code'    => 'not_allowed',
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        $key = "activate-{$plugin}";

        $response = [];
        $response['status'] = 'activate';
        $response['nonce']  = wp_create_nonce($key);
        wp_send_json_success($response);
    }

    /**
     * AJAX Request
     * ----
     * Activate the plugin.
     * 
     * @uses wp_send_json_success()
     * @uses wp_send_json_error()
     * 
     * @return array
     */
    public function activatePlugin()
    {
        $request = $_REQUEST;

        $request = $_REQUEST;
        $nonce   = sanitize_text_field($request['nonce']);
        $plugin  = sanitize_text_field($request['slug']);

        $nonceKey = "activate-{$plugin}";

        $response = [];

        if (!wp_verify_nonce($nonce, $nonceKey)) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'code'    => 'not_allowed',
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        $pluginFile = sanitize_text_field($request['coreFile']);

        $status = theme404_ocdi()->plugins()->activate($pluginFile);

        if (!$status) {
            wp_send_json_error();
        }

        $response = [];
        $response['status'] = 'activated';
        wp_send_json_success($response);
    }

    /**
     * AJAX Request
     * ----
     * Prepares the import files.
     * 
     * @return wp_send_json_{success|error} Success or Error depending upon.
     */
    public function prepareImport()
    {
        global $wpdb;

        $request = $_REQUEST;



        if (!wp_verify_nonce($request['nonce'])) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        $theme        = theme404_ocdi_get_theme();
        $slug         = sanitize_text_field($request['slug']);

        $steps        = $request['steps'];
        $demo         = theme404_ocdi()->demo($slug);

        $importer     = theme404_ocdi()->importer();
        $writtenFiles = $importer->prepare($demo);

        if (is_array($writtenFiles) && count($writtenFiles) > 0) {
            
            $wpdb->query("DELETE FROM $wpdb->posts");
            $wpdb->query("DELETE FROM $wpdb->postmeta");

            // We need the files to proceed further.
            $response = [
                'files'  => $writtenFiles,
                'steps'  => $steps,
                'action' => 'import-content',
                'nonce'  => wp_create_nonce('import-content'),
                'demo'   => $slug,
            ];

            wp_send_json_success($response);
        }


        $response = [
            'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
            'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
        ];

        wp_send_json_error($response);
    }

    /**
     * AJAX Request
     * ----
     * Imports the content
     * 
     * @return wp_send_json_{success|error} Success or Error depending upon.
     */
    public function importContent()
    {
        $request = $_REQUEST;

        if (!wp_verify_nonce($request['nonce'], 'import-content')) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        unset($request['nonce']);
        unset($request['action']);

        // Actually begin the import process.
        $slug       = $request['slug'];

        $demosDir   = theme404_ocdi_get_demos_dir($slug);
        $files      = $request['files'];
        $filename   = $files['content'];


        $file       = wp_normalize_path("{$demosDir}/$filename");

        $import     = theme404_ocdi()->importer()->content($file, $request);


        $steps    = $request['steps'];
        $index    = isset($request['stepsIndex']) ? $request['stepsIndex'] + 1 : 1;
        $nextStep = $steps[$index];


        $nonceKey = "import-{$nextStep}";

        $response = [
            'nonce'  => wp_create_nonce($nonceKey),
            'action' => $nextStep,
            'steps'  => $steps,
            'files'  => $request['files'],
        ];

        wp_send_json_success($response);
    }

    /**
     * AJAX Request
     * ----
     * Imports the customizer data.
     * 
     * @return wp_send_json_{success|error} Success or Error depending upon.
     */
    public function importCustomize()
    {
        global $wp_customize;
        $request = $_REQUEST;

        if (!wp_verify_nonce($request['nonce'], 'import-customizer')) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        if (
            !class_exists('WP_Customize_Manager', false) &&
            !$wp_customize instanceof WP_Customize_Manager
        ) {
            // Load customize manager.
            include_once ABSPATH . 'wp-includes/class-wp-customize-manager.php';

            /**
             * We're not passing any data cause we'll be resetting 
             * this instance once our data is imported.
             */
            $wp_customize = new WP_Customize_Manager();
        }

        // Actually begin the import process.
        $slug       = $request['slug'];

        $demosDir   = theme404_ocdi_get_demos_dir($slug);
        $files      = $request['files'];

        $filename   = $files['customizer'];


        $file       = wp_normalize_path("{$demosDir}/$filename");

        $status      = theme404_ocdi()->importer()->customizer($wp_customize, $file);

        $steps    = $request['steps'];
        $index    = (int) $request['stepsIndex'] + 1;
        $nextStep = $steps[$index];
        $nonceKey = "import-{$nextStep}";

        $response = [
            'nonce'  => wp_create_nonce($nonceKey),
            'action' => $nextStep,
            'steps'  => $steps,
            'files'  => $request['files'],
        ];

        // Set the wp_customize to null so that we don't face any problems.
        $wp_customize = null;

        wp_send_json_success($response);
    }

    /**
     * AJAX Request
     * ----
     * Imports the widget data.
     * 
     * @return wp_send_json_{success|error} Success or Error depending upon.
     */
    public function importWidget()
    {
        $request = $_REQUEST;

        if (!wp_verify_nonce($request['nonce'], 'import-widgets')) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        // Actually begin the import process.
        $slug       = $request['slug'];

        $demosDir   = theme404_ocdi_get_demos_dir($slug);
        $files      = $request['files'];

        $filename   = $files['widgets'];


        $file       = wp_normalize_path("{$demosDir}/$filename");

        $status     = theme404_ocdi()->importer()->widgets($file);

        $steps = $request['steps'];
        $index = (int) $request['stepsIndex'] + 1;
        $nextStep = $steps[$index];

        $nonceKey = "import-{$nextStep}";

        $response = [
            'nonce'  => wp_create_nonce($nonceKey),
            'action' => $nextStep,
            'steps'  => $steps,
            'files'  => $request['files'],
        ];

        wp_send_json_success($response);
    }

    /**
     * AJAX Request
     * ----
     * Imports the slider.
     * 
     * @return wp_send_json_{success|error} Success or Error depending upon.
     */
    public function importSlider()
    {
        $request = $_REQUEST;

        if (!wp_verify_nonce($request['nonce'], 'import-slider')) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        // Import the slider.
        $slug       = $request['slug'];

        $demosDir   = theme404_ocdi_get_demos_dir($slug);
        $files      = $request['files'];
        $sliders    = $files['slider'];

        if (class_exists('SmartSlider3') && is_array($sliders) && count($sliders) > 0) {

            require_once THEME404_OCDI_IMPORTER . 'class-theme404-ocdi-smart-slider.php';

            Theme404_OCDI_Smart_Slider::delete();

            foreach ($sliders as $slider) {
                $sliderFile = $slider['file'];
                $file = "{$demosDir}/{$sliderFile}";
                theme404_ocdi()->importer()->slider($file);
            }
        }

        $steps = $request['steps'];
        $index = (int) $request['stepsIndex'] + 1;
        $nextStep = $steps[$index];

        $nonceKey = "import-{$nextStep}";

        $response = [
            'nonce'  => wp_create_nonce($nonceKey),
            'action' => $nextStep,
            'steps'  => $steps,
            'files'  => $request['files'],
        ];

        wp_send_json_success($response);
    }

    /**
     * AJAX Request
     * ----
     * Imports the menu.
     * 
     * @return wp_send_json_{success|error} Success or Error depending upon.
     */
    public function importMenu()
    {
        $request = $_REQUEST;

        if (!wp_verify_nonce($request['nonce'], 'import-menu')) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        $slug       = $request['slug'];
        $demo       = theme404_ocdi()->demo($slug);

        $navigation = $demo['menus'];

        theme404_ocdi()->importer()->setupNavigation($navigation);

        $steps = $request['steps'];
        $index = (int) $request['stepsIndex'] + 1;
        $nextStep = $steps[$index];

        $nonceKey = "import-{$nextStep}";

        $response = [
            'nonce'  => wp_create_nonce($nonceKey),
            'action' => $nextStep,
            'steps'  => $steps,
            'files'  => $request['files'],
        ];

        wp_send_json_success($response);
    }

    /**
     * AJAX Request
     * ----
     * Basicallys sets up the page reading.
     * 
     * @return wp_send_json_{success|error} Success or Error depending upon.
     */
    public function importPages()
    {
        $request = $_REQUEST;

        if (!wp_verify_nonce($request['nonce'], 'import-pages')) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        // Setup the pages.

        $slug       = $request['slug'];
        $demo       = theme404_ocdi()->demo($slug);

        $pages       = $demo['pages'];

        $wcSupport   = false;

        if (isset($demo['wcSupport']) && $demo['wcSupport']) {
            $wcSupport = true;
        }

        $frontPage = $pages['homepage'];
        $blogPage  = (isset($pages['postpage'])) ? $pages['postpage'] : 0;

        $homePage  = get_page_by_title($frontPage);
        $postsPage = get_page_by_title($blogPage);

        if (isset($homePage->ID)) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $homePage->ID);
        }

        if (isset($postsPage->ID)) {
            update_option('page_for_posts', $postsPage->ID);
        }

        if ($wcSupport) {
            $wc_pages = [
                'shop'          => 'Store',
                'cart'          => 'Cart',
                'checkout'      => 'Checkout',
                'myaccount'     => 'My account',
            ];

            // Setup WooCommerce Pages.
            if (is_array($wc_pages) && function_exists('WC') && count($wc_pages) > 0) {

                foreach ($wc_pages as $slug => $title) {

                    $woopage = get_page_by_title(html_entity_decode($title));
                    if (isset($woopage) && property_exists($woopage, 'ID')) {

                        // prepare WooCommerce option slug where pages are stored.
                        $key = "woocommerce_{$slug}_page_id";
                        update_option($key, $woopage->ID);
                    }
                }
            }
        }

        $steps = $request['steps'];
        $index = (int) $request['stepsIndex'] + 1;
        $nextStep = $steps[$index];

        $nonceKey = "import-{$nextStep}";

        $response = [
            'nonce'  => wp_create_nonce($nonceKey),
            'action' => $nextStep,
            'steps'  => $steps,
            'files'  => $request['files'],
        ];

        wp_send_json_success($response);
    }

    /**
     * AJAX Request
     * ----
     * Finalize the import.
     * 
     * @return wp_send_json_{success|error} Success or Error depending upon.
     */
    public function finalize()
    {
        $request = $_REQUEST;

        if (!wp_verify_nonce($request['nonce'], 'import-finalize')) {
            // Nonce cannot be verified. Try again later.
            $response = [
                'title'   => esc_html__('Sorry!', 'theme404-one-click-demo-import'),
                'message' => esc_html__('This action cannot be performed now. Please try again later!', 'theme404-one-click-demo-import')
            ];

            wp_send_json_error($response);
        }

        // TODO: Finalize the import process.
        flush_rewrite_rules(true);

        $steps = $request['steps'];
        $index = $request['stepsIndex'];
        $nextStep = $steps[$index];

        $response = [
            'action' => 'finalized',
        ];

        wp_send_json_success($response);
    }
}
