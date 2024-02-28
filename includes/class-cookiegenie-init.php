<?php
/**
 * Settings class file.
 *
 * @package WordPress Plugin Template/Settings
 */

if (!defined('ABSPATH'))
    exit;

class CookieGenie_Init
{

    /**
     * The single instance of CookieGenie_Settings.
     *
     * @var     object
     * @access  private
     * @since   1.0.0
     */
    private static $_instance = null;

    /**
     * The main plugin object.
     *
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public $parent = null;

    public function __construct($parent)
    {
        $this->parent = $parent;

        if (get_option('cg_enabled')) {

            // enqueue scripts if user hasn't consent yet
            if ($this->askConsent())
                add_action('wp_head', array($this, 'enqueueScripts'), -99999);
            else {
                add_action('wp_head', array($this, 'enqueueConsentScripts'), -99999);
                add_action('wp_enqueue_scripts', array($this, 'enqueueGoogleConsentScripts'), 100);
            }

            if (!isset($_COOKIE['cookiegenie_consent']) && !isset($_COOKIE['cookiegenie_block']))
                add_action('wp_enqueue_scripts', array($this, 'renderCookieConsent'), 1000);

        }
    }

    public function enqueueConsentScripts()
    {
        $init_script = esc_url($this->parent->assets_url) . 'js/cookiegenie_consent.js';
        include(esc_url($this->parent->assets_dir) . '/scripts/cookiegenie-script.php');
    }

    public function enqueueGoogleConsentScripts() {
        wp_enqueue_script(
            $this->parent->_token . '-frontendGc',
            esc_url($this->parent->assets_url) . '/js/googleConsent.js',
            array('jquery'),
            $this->parent->_version,
            true
        );
    }

    public function enqueueScripts()
    {
        $init_script = esc_url($this->parent->assets_url) . 'js/cookiegenie_init.js';
        include(esc_url($this->parent->assets_dir) . '/scripts/cookiegenie-script.php');
    }

    public function renderCookieConsent()
    {
        wp_enqueue_script($this->parent->_token . '-cgb', esc_url($this->parent->assets_url) . 'js/banner.js', array('jquery'), $this->parent->_version);
        wp_localize_script($this->parent->_token . '-cgb', 'banner', $this->getBannerData());
    }

    public static function instance($parent)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($parent);
        }
        return self::$_instance;
    }

    protected function getData()
    {
        return [
            "blacklist" => preg_split('/\r\n|\r|\n/', get_option('cg_blacklist')),
            "whitelist" => preg_split('/\r\n|\r|\n/', get_option('cg_whitelist')),
        ];
    }

    protected function getBannerData()
    {
        return [
            'bck_color' => esc_attr(get_option('cg_bck_color')),
            'scn_color' => esc_attr(get_option('cg_scn_color')),
            'cookietitle' => esc_html(get_option('cg_cookietitle')),
            'cookietext' => esc_html(get_option('cg_cookietext')),
            'cookiedeclaration' => esc_url(get_option('cg_cookiedeclaration')),
            'readmore' => esc_html__('Read more', 'cookiegenie'),
            'btn_disallow' => esc_html__('Only necessary cookies', 'cookiegenie'),
            'btn_allow' => esc_html__('Allow all cookies', 'cookiegenie')
        ];
    }

    protected function askConsent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']) && in_array($_SERVER['HTTP_USER_AGENT'], ['google', 'spider', 'slurp', 'crawl', 'bot', 'yahoo']))
            return false;

        if(isset($_COOKIE['cookiegenie_consent']))
            return false;

        return true;
    }
}