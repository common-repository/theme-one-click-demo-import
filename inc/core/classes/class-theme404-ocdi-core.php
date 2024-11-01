<?php



/**
 * Theme404 One Click Demo Import Core.
 *
 * This class handles few things.
 * 1. Downloads the required demo files.
 * 2. Imports Content
 * 3. Imports Customizer Informations.
 * 4. Imports Widgets.
 * 5. Sets up pages.
 * 6. Finalizes the Import Process.
 *
 * @since 1.0.0
 */
if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class:: Theme404_OCDI_Core.
 *
 * Content Importer.
 */
class Theme404_OCDI_Core
{
    /**
     * Single class instance.
     *
     * @since 1.0.0
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Records the time.
     *
     * @since 1.0.0
     *
     * @var object
     */
    private $microtime;

    /**
     * Stores the request instance to prevent failure of nonce validation
     * @provide the complete request object for AJAX.
     */
    protected $request = [];

    /**
     * Ensures only one instance of this class is available.
     *

     *
     * @version 1.0.0
     *
     * @since 1.0.0
     *
     * @return object Theme404_OCDI_Core
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Theme404_OCDI_Core::getInstance()
     * @since 1.0.0
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
     * You cannot unserialize instance of this class.
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
     * Inject the importer where required.
     */
    private function injectImporter()
    {
        if (!class_exists('WP_Importer')) {
            defined('WP_LOAD_IMPORTERS') || define('WP_LOAD_IMPORTERS', true);
            require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
        }

        // if (!class_exists('WXR_Importer')) {
        //     require THEME404_OCDI_CLASSES . 'importer/class-wxr-importer.php';
        // }

        if (!class_exists('WXR_Importer_V2')) {
            require THEME404_OCDI_CLASSES . 'importer/class-wxr-importer-v2.php';
        }
    }

    /**
     * Prepares the import.
     *
     * @param array Demo
     *
     * @return bool
     */
    public function prepare($demo)
    {
        $slug = $demo['slug'];

        $dir = $this->createDir($slug);

        $files = $this->download($demo, $dir);

        return $files;
    }

    /**
     * Creates the directory.
     * 
     * @param string $slug.
     */
    public function createDir($slug)
    {
        $dir = theme404_ocdi_get_custom_uploads_dir();
        $path  = "{$dir}/$slug";

        if (!file_exists($path)) {
            wp_mkdir_p(trailingslashit($path));
        }

        return $path;
    }

    /**
     * Downloads the file.
     * 
     * @param array $files  List of files to be downloaded.
     * @param string $dir   Download path for the files.
     * 
     * @return array Files downloaded & written.
     */
    public function download($demo, $dir)
    {
        $name = $demo['slug'];

        $args = [];
        $args['theme'] = (isset($demo['theme'])) ? $demo['theme'] : 'neostore';
        $args['demo'] = $name;

        if (!function_exists('download_url')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $files = [];

        if (isset($demo['files'])) {

            foreach ($demo['files'] as $key => $file) {

                if (empty($file)) {
                    continue;
                }

                if (is_array($file)) {
                    foreach ($file as $filename) {
                        $name = $filename['file'];

                        $file_name = "{$dir}/{$name}";

                        $args['file'] = $name;
                        $url = theme404_ocdi()->api()->downloadUrl($args, $dir);

                        // Download the file.
                        $download = download_url($url);

                        // Get the file content.
                        $content = @file_get_contents($download);

                        $file_handle = @fopen($file_name, 'w'); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen

                        if ($file_handle) {
                            $files[$key] = $file;

                            fwrite($file_handle, $content); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite

                            fclose($file_handle); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
                        }
                    }
                } else {
                    $file_name = "{$dir}/$file";

                    // Check if file exists.
                    if (file_exists($file_name)) {
                        $files[$key] = $file;
                        continue;
                    }

                    $args['file'] = $file;
                    $url = theme404_ocdi()->api()->downloadUrl($args, $dir);

                    // Download the file.
                    $download = download_url($url);


                    // Get the file content.
                    $content = @file_get_contents($download);

                    $file_handle = @fopen($file_name, 'w'); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen

                    if ($file_handle) {
                        $files[$key] = $file;

                        fwrite($file_handle, $content); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite

                        fclose($file_handle); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Begin the import process.
     * 
     * @return bool
     */
    public function content($file, $request = [])
    {

        $this->injectImporter();

        if (!empty($request)) {
            $this->request = $request;
        }

        // Increase the time limit to max.
        set_time_limit(120);

        ini_set('memory_limit', '350M');

        // Disable import of authors.
        add_filter('wxr_importer.pre_process.user', '__return_false');

        // Check, if we need to send another AJAX request and set the importing author to the current user.
        add_filter('wxr_importer.pre_process.post', [$this, 'checkTimeout']);

        $options = [
            'fetch_attachments' => true,
        ];

        $this->microtime = microtime(true);

        $wxr_importer = new WXR_Importer_V2($options);

        $xml_file = wp_normalize_path($file);


        try {
            $result = $wxr_importer->import($xml_file);

            if (is_wp_error($result)) {
                return ['action' => 'terminate', 'message' => $result->get_error_message()];
            } else {
                // next action to trigger
                $response = [
                    'message'    => esc_html__('Importing Options', 'theme404-one-click-demo-import'),
                    'action'     => 'import-customize',
                ];

                return $response;
            }
        } catch (Exception $e) {
            return ['action'  => 'terminate', 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Does two things
     * Checks the AJAX time & return reponse to start new AJAX request
     * OR
     * Stops the import of multiple author & assign to current author
     */
    public function checkTimeout($data)
    {
        $time = microtime(true) - $this->microtime;

        // We should make a new ajax call, if the time is right.
        if ($time > 20) {

            $response = $this->request;

            $response['action'] = 'content-import';
            $response['recurse'] = true;
            $response['message'] = "Time for new AJAX: {$time}";
            $response['nonce'] = wp_create_nonce('import-content');

            // Send the request for a new AJAX call.
            wp_send_json_success($response);
        }

        // Set importing author to the current user.
        // Fixes the [WARNING] Could not find the author for ... log warning messages.
        $current_user    = wp_get_current_user();
        $data['post_author'] = $current_user->user_login;

        return $data;
    }


    public function customizer($wp_customize, $file)
    {
        if (!class_exists('Theme404_OCDI_Customize_Importer')) {
            require_once THEME404_OCDI_IMPORTER . 'class-theme404-ocdi-customize-importer.php';
        }

        $customize = new Theme404_OCDI_Customize_Importer();
        $customize->import($wp_customize, $file);

        return true;
    }

    /**
     * Import the widget.
     * 
     * @return bool
     */
    public function widgets($file)
    {
        global $wp_registered_sidebars;

        $fileContents = file_get_contents($file);

        $data = json_decode($fileContents);

        // Have valid data?
        // If no data or could not decode.
        if (empty($data) || !is_object($data)) {

            wp_die(
                esc_html__('Import data could not be read. Please try a different file.', 'theme404-one-click-demo-import'),
                '',
                array(
                    'back_link' => true,
                )
            );
        }

        // Hook before import.
        do_action('wie_before_import');
        $data = apply_filters('wie_import_data', $data);

        // Get all available widgets site supports.
        $available_widgets = theme404_ocdi_get_available_widgets();

        // Get all existing widget instances.
        $widget_instances = array();
        foreach ($available_widgets as $widget_data) {
            $widget_instances[$widget_data['id_base']] = get_option('widget_' . $widget_data['id_base']);
        }

        // Begin results.
        $results = array();

        // Loop import data's sidebars.
        foreach ($data as $sidebar_id => $widgets) {

            // Skip inactive widgets (should not be in export file).
            if ('wp_inactive_widgets' === $sidebar_id) {
                continue;
            }

            // Check if sidebar is available on this site.
            // Otherwise add widgets to inactive, and say so.
            if (isset($wp_registered_sidebars[$sidebar_id])) {
                $sidebar_available    = true;
                $use_sidebar_id       = $sidebar_id;
                $sidebar_message_type = 'success';
                $sidebar_message      = '';
            } else {
                $sidebar_available    = false;
                $use_sidebar_id       = 'wp_inactive_widgets'; // Add to inactive if sidebar does not exist in theme.
                $sidebar_message_type = 'error';
                $sidebar_message      = esc_html__('Widget area does not exist in theme (using Inactive)', 'theme404-one-click-demo-import');
            }

            // Result for sidebar
            // Sidebar name if theme supports it; otherwise ID.
            $results[$sidebar_id]['name']         = !empty($wp_registered_sidebars[$sidebar_id]['name']) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id;
            $results[$sidebar_id]['message_type'] = $sidebar_message_type;
            $results[$sidebar_id]['message']      = $sidebar_message;
            $results[$sidebar_id]['widgets']      = array();

            // Loop widgets.
            foreach ($widgets as $widget_instance_id => $widget) {

                $fail = false;

                // Get id_base (remove -# from end) and instance ID number.
                $id_base            = preg_replace('/-[0-9]+$/', '', $widget_instance_id);
                $instance_id_number = str_replace($id_base . '-', '', $widget_instance_id);

                // Does site support this widget?
                if (!$fail && !isset($available_widgets[$id_base])) {
                    $fail                = true;
                    $widget_message_type = 'error';
                    $widget_message = esc_html__('Site does not support widget', 'theme404-one-click-demo-import'); // Explain why widget not imported.
                }

                // Filter to modify settings object before conversion to array and import
                // Leave this filter here for backwards compatibility with manipulating objects (before conversion to array below)
                // Ideally the newer wie_widget_settings_array below will be used instead of this.
                $widget = apply_filters('wie_widget_settings', $widget);

                // Convert multidimensional objects to multidimensional arrays
                // Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
                // Without this, they are imported as objects and cause fatal error on Widgets page
                // If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach: https://wordpress.org/support/topic/problem-with-array-of-arrays
                // It is probably much more likely that arrays are used than objects, however.
                $widget = json_decode(wp_json_encode($widget), true);

                // Filter to modify settings array
                // This is preferred over the older wie_widget_settings filter above
                // Do before identical check because changes may make it identical to end result (such as URL replacements).
                $widget = apply_filters('wie_widget_settings_array', $widget);

                // Does widget with identical settings already exist in same sidebar?
                if (!$fail && isset($widget_instances[$id_base])) {

                    // Get existing widgets in this sidebar.
                    $sidebars_widgets = get_option('sidebars_widgets');
                    $sidebar_widgets = isset($sidebars_widgets[$use_sidebar_id]) ? $sidebars_widgets[$use_sidebar_id] : array(); // Check Inactive if that's where will go.

                    // Loop widgets with ID base.
                    $single_widget_instances = !empty($widget_instances[$id_base]) ? $widget_instances[$id_base] : array();
                    foreach ($single_widget_instances as $check_id => $check_widget) {

                        // Is widget in same sidebar and has identical settings?
                        if (in_array("$id_base-$check_id", $sidebar_widgets, true) && (array) $widget === $check_widget) {

                            $fail = true;
                            $widget_message_type = 'warning';

                            // Explain why widget not imported.
                            $widget_message = esc_html__('Widget already exists', 'theme404-one-click-demo-import');

                            break;
                        }
                    }
                }

                // No failure.
                if (!$fail) {

                    // Add widget instance
                    $single_widget_instances = get_option('widget_' . $id_base); // All instances for that widget ID base, get fresh every time.
                    $single_widget_instances = !empty($single_widget_instances) ? $single_widget_instances : array(
                        '_multiwidget' => 1, // Start fresh if have to.
                    );
                    $single_widget_instances[] = $widget; // Add it.

                    // Get the key it was given.
                    end($single_widget_instances);
                    $new_instance_id_number = key($single_widget_instances);

                    // If key is 0, make it 1
                    // When 0, an issue can occur where adding a widget causes data from other widget to load,
                    // and the widget doesn't stick (reload wipes it).
                    if ('0' === strval($new_instance_id_number)) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset($single_widget_instances[0]);
                    }

                    // Move _multiwidget to end of array for uniformity.
                    if (isset($single_widget_instances['_multiwidget'])) {
                        $multiwidget = $single_widget_instances['_multiwidget'];
                        unset($single_widget_instances['_multiwidget']);
                        $single_widget_instances['_multiwidget'] = $multiwidget;
                    }

                    // Update option with new widget.
                    update_option('widget_' . $id_base, $single_widget_instances);

                    // Assign widget instance to sidebar.
                    // Which sidebars have which widgets, get fresh every time.
                    $sidebars_widgets = get_option('sidebars_widgets');

                    // Avoid rarely fatal error when the option is an empty string
                    // https://github.com/churchthemes/theme404-one-click-demo-import/pull/11.
                    if (!$sidebars_widgets) {
                        $sidebars_widgets = array();
                    }

                    // Use ID number from new widget instance.
                    $new_instance_id = $id_base . '-' . $new_instance_id_number;

                    // Add new instance to sidebar.
                    $sidebars_widgets[$use_sidebar_id][] = $new_instance_id;

                    // Save the amended data.
                    update_option('sidebars_widgets', $sidebars_widgets);

                    // After widget import action.
                    $after_widget_import = array(
                        'sidebar'           => $use_sidebar_id,
                        'sidebar_old'       => $sidebar_id,
                        'widget'            => $widget,
                        'widget_type'       => $id_base,
                        'widget_id'         => $new_instance_id,
                        'widget_id_old'     => $widget_instance_id,
                        'widget_id_num'     => $new_instance_id_number,
                        'widget_id_num_old' => $instance_id_number,
                    );
                    do_action('wie_after_widget_import', $after_widget_import);

                    // Success message.
                    if ($sidebar_available) {
                        $widget_message_type = 'success';
                        $widget_message      = esc_html__('Imported', 'theme404-one-click-demo-import');
                    } else {
                        $widget_message_type = 'warning';
                        $widget_message      = esc_html__('Imported to Inactive', 'theme404-one-click-demo-import');
                    }
                }

                // Result for widget instance
                $results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset($available_widgets[$id_base]['name']) ? $available_widgets[$id_base]['name'] : $id_base; // Widget name or ID if name not available (not supported by site).
                $results[$sidebar_id]['widgets'][$widget_instance_id]['title']        = !empty($widget['title']) ? $widget['title'] : esc_html__('No Title', 'theme404-one-click-demo-import'); // Show "No Title" if widget instance is untitled.
                $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
                $results[$sidebar_id]['widgets'][$widget_instance_id]['message']      = $widget_message;
            }
        }

        // Return results.
        return apply_filters('wie_import_results', $results);
    }

    /**
     * Import the slider datas.
     * 
     * @return bool.
     */
    public function slider($file)
    {
        // Smart Slider plugin is inactive.
        if (!class_exists('SmartSlider3')) {
            return true;
        }

        SmartSlider3::import($file);

        return true;
    }

    /**
     * Setup pages.
     * 
     * @return bool.
     */
    public function setupPages($pages)
    {


        return true;
    }

    /**
     * Setup menu navigation.
     * 
     * @return bool.
     */
    public function setupNavigation($navigations)
    {
        $locations = get_theme_mod('nav_menu_locations');

        foreach ($navigations as $key => $value) {
            $menu = get_term_by('name', $value, 'nav_menu');

            if (isset($menu->term_id)) {
                $locations[$key] = $menu->term_id;
            }
        }

        set_theme_mod('nav_menu_locations', $locations);

        return true;
    }
}
