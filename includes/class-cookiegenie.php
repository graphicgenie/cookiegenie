<?php
/**
 * Main plugin class file.
 *
 * @package WordPress Plugin Template/Includes
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class.
 */
class CookieGenie {

    /**
     * The single instance of CookieGenie.
     *
     * @var    object
     * @access private
     * @since  1.0.0
     */
    private static $_instance = null; //phpcs:ignore

    /**
     * Local instance of CookieGenie_Admin_API
     *
     * @var CookieGenie_Admin_API|null
     */
    public $admin = null;

    /**
     * Settings class object
     *
     * @var    object
     * @access public
     * @since  1.0.0
     */
    public $settings = null;

    /**
     * The version number.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $_version; //phpcs:ignore

    /**
     * The token.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $_token; //phpcs:ignore

    /**
     * The main plugin file.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $file;

    /**
     * The main plugin directory.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $dir;

    /**
     * The plugin assets directory.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $assets_dir;

    /**
     * The plugin assets URL.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $assets_url;

    /**
     * Suffix for JavaScripts.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $script_suffix;

    /**
     * Initial setup data
     *
     * @var    string[]
     * @access protected
     * @since  1.0.0
     */
    protected $initial_data = 'YToyOntpOjA7czoyMDoiZ29vZ2xlLWFuYWx5dGljcy5jb20iO2k6MTtzOjIzOiJzdGF0cy5nLmRvdWJsZWNsaWNrLm5ldCI7fQ==';


    /**
     * Constructor funtion.
     *
     * @param string $file File constructor.
     * @param string $version Plugin version.
     */
    public function __construct($file = '', $version = '1.0.0')
    {
        $this->_version = $version;
        $this->_token = 'cookiegenie';

        // Load plugin environment variables.
        $this->file = $file;
        $this->dir = dirname($this->file);
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));

        $this->script_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        register_activation_hook($this->file, [$this, 'install']);
        register_deactivation_hook($this->file, [$this, 'uninstall']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles'], 10);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 10);

        // Load admin JS & CSS.
//        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'], 10, 1);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_styles'], 10, 1);

        // Schedule Cron
        add_action('cg_update_lists', [$this, 'cg_update_lists'], 10, 1);

        // Load API for generic admin functions.
        if (is_admin()) {
            $this->admin = new CookieGenie_Admin_API();
        }

        // Handle localisation.
        $this->load_plugin_textdomain();

        add_action('init', [$this, 'load_localisation'], 0);

    }//end __construct()


    // End __construct ()


    /**
     * Load frontend CSS.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function enqueue_styles()
    {
        wp_register_style($this->_token . '-frontend', esc_url($this->assets_url) . 'css/frontend.css', [], $this->_version);
        wp_enqueue_style($this->_token . '-frontend');

    }//end enqueue_styles()


    // End enqueue_styles ()


    /**
     * Load frontend Javascript.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script($this->_token . '-frontend', esc_url($this->assets_url) . 'js/frontend.js', ['jquery'], $this->_version, true);
        wp_enqueue_script($this->_token . '-frontend');
        wp_localize_script($this->_token . '-frontend', 'data', ['expire' => get_option('cg_expire'), 'version' => $this->_version]);

    }//end enqueue_scripts()


    // End enqueue_scripts ()


    /**
     * Admin enqueue style.
     *
     * @param string $hook Hook parameter.
     *
     * @return void
     */
    public function admin_enqueue_styles($hook = '')
    {
        wp_register_style($this->_token . '-admin', esc_url($this->assets_url) . 'css/admin.css', [], $this->_version);
        wp_enqueue_style($this->_token . '-admin');

    }//end admin_enqueue_styles()


    // End admin_enqueue_styles ()


    /**
     * Load admin Javascript.
     *
     * @access public
     *
     * @param string $hook Hook parameter.
     *
     * @return void
     * @since  1.0.0
     */
    public function admin_enqueue_scripts($hook = '')
    {
        wp_register_script($this->_token . '-admin', esc_url($this->assets_url) . 'js/admin' . $this->script_suffix . '.js', ['jquery'], $this->_version, true);
        wp_enqueue_script($this->_token . '-admin');

    }//end admin_enqueue_scripts()


    // End admin_enqueue_scripts ()


    /**
     * Load plugin localisation
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function load_localisation()
    {
        load_plugin_textdomain('cookiegenie', false, dirname(plugin_basename($this->file)) . '/lang/');

    }//end load_localisation()


    // End load_localisation ()


    /**
     * Load plugin textdomain
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function load_plugin_textdomain()
    {
        $domain = 'cookiegenie';

        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, dirname(plugin_basename($this->file)) . '/lang/');

    }//end load_plugin_textdomain()


    // End load_plugin_textdomain ()


    /**
     * Main CookieGenie Instance
     *
     * Ensures only one instance of CookieGenie is loaded or can be loaded.
     *
     * @param string $file File instance.
     * @param string $version Version parameter.
     *
     * @return object CookieGenie instance
     * @see    CookieGenie()
     * @since  1.0.0
     * @static
     */
    public static function instance($file = '', $version = '1.0.0')
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }

        return self::$_instance;

    }//end instance()


    // End instance ()


    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, esc_html(__('Cloning of CookieGenie is forbidden')), esc_attr($this->_version));

    }//end __clone()


    // End __clone ()


    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, esc_html(__('Unserializing instances of CookieGenie is forbidden')), esc_attr($this->_version));

    }//end __wakeup()


    // End __wakeup ()


    /**
     * Installation. Runs on activation.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function install()
    {
        $this->_log_version_number();

        update_option('cg_blacklist', implode(PHP_EOL, unserialize(base64_decode($this->initial_data))));
        update_option('cg_cookietitle', 'This website is using cookies');
        update_option('cg_cookietext', 'We use cookies to make our website function properly, to improve it, to enable social media functions and to analyze traffic to our website. This information is also shared with our social media and analytics partners. More information about this can be found in our Cookie Statement.');
        update_option('cg_bck_color', '#1F3163');
        update_option('cg_scn_color', '#FFFFFF');

        if (!wp_next_scheduled('cg_update_lists'))
            wp_schedule_event(time(), 'hourly', 'cg_update_lists');

        $this->cg_update_lists();

    }//end install()


    // End install ()


    /**
     * Installation. Runs on deactivation.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function uninstall()
    {
        // remove scheduled update
        $timestamp = wp_next_scheduled('cg_update_lists');
        wp_unschedule_event($timestamp, 'cg_update_lists');

    }//end uninstall()


    /**
     * Setup cron for data update
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function cg_update_lists()
    {
        $api_key = get_option('cg_api_key');

        if ($api_key != '') {
            $domain = str_ireplace('www.', '', parse_url(get_bloginfo('url'), PHP_URL_HOST));
            $args = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                ],
            ];
            $response = wp_remote_get('https://cookiegenie.ggcloud.nl/api?domain=' . $domain, $args);

            if (is_array($response) && !isset($response['errors']))
                update_option('cg_blacklist', implode(PHP_EOL, unserialize($response['body'])));

            if (is_wp_error($response))
                error_log($response->get_error_message());
        }

        $this->createJS();

    }//end cg_update_lists()


    /**
     * Log the plugin version number.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    private function _log_version_number()
    { //phpcs:ignore
        update_option($this->_token . '_version', $this->_version);

    }//end _log_version_number()


    // End _log_version_number ()
    protected function createJS()
    {
        $script = file_get_contents(esc_url($this->assets_dir) . '/js/cookiegenie_helper.js');
        $script .= "let data = " . json_encode($this->getData()) . ";
        YETT_BLACKLIST = [];
        data.blacklist.forEach(function (value) {
            if(value !== '') {
                YETT_BLACKLIST.push(new RegExp(escapeRegExp(encodeURIComponent(value))));
            };
        });
        YETT_WHITELIST = [];
        data.whitelist.forEach(function (value) {
            if(value !== '') {
                YETT_WHITELIST.push(new RegExp(escapeRegExp(encodeURIComponent(value))));
            };
        });
        ";
        $script .= file_get_contents(esc_url($this->assets_dir) . '/js/yett.min.js');

        $minifiedCode = \JShrink\Minifier::minify($script);

        file_put_contents(esc_url($this->assets_dir) . '/js/cookiegenie_init.js', $minifiedCode);
    }

    protected function getData()
    {
        return [
            "blacklist" => preg_split('/\r\n|\r|\n/', esc_html(get_option('cg_blacklist'))),
            "whitelist" => preg_split('/\r\n|\r|\n/', esc_html(get_option('cg_whitelist'))),
        ];
    }
}//end class
