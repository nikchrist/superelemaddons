<?php 

final class SAelementor{

  public function __construct(){

  add_action('init',array($this,'i18n'));
  add_action('plugins_loaded',array($this,'init'));
  }

  public function i18n(){
    load_plugin_textdomain(SA_PLUGIN_DOMAIN);
  }

  public function init(){

    $plugin_woo_path = trailingslashit(WP_PLUGIN_DIR).'woocommerce/woocommerce.php';

    if(!did_action('elementor/loaded'))
    {
      add_action('admin_notices',[$this,'admin_notice_missing_elementor']);
      return;
    }

    if(version_compare(ELEMENTOR_VERSION,MINIMUM_ELEMENTOR_VERSION,'<') )
    {
      add_action('admin_notices',[$this,'admin_notice_elementor_minimum_version']);
      return;
    }

    if(!in_array($plugin_woo_path,wp_get_active_and_valid_plugins()))
    {
      add_action('admin_notices',[$this,'admin_notice_missing_woocommerce_plugin']);
      return;
    }

  }

  /* CHECK IF ELEMENTOR IS ACTIVATED */
  public function admin_notice_missing_elementor(){
    deactivate_plugins(plugin_basename(__FILE__));

    if(isset($_GET['activate']) ) 
    {
      unset($_GET['activate']);
    }

    echo "<div class='notice notice-warning is-dismissible>".
    esc_html__("SUPPER ELEMENTOR ADDONS plugin requires ELEMENTOR plugin",SA_PLUGIN_DOMAIN).
        "</div>";
  }

/* CHECK IF CURRENT ELEMENTOR PLUGIN VERSION IS HIGHER THAN THE MINIMUM ELEMENTOR VERSION */
  public function admin_notice_elementor_minimum_version(){
    deactivate_plugins(plugin_basename(__FILE__));
    if(isset($_GET['activate']) ) 
    {
      unset($_GET['activate']);
    }

    echo "<div class='notice notice-warning is-dismissible>".
    esc_html__("current elementor version".ELEMENTOR_VERSION."must be higher than".MINIMUM_ELEMENTOR_VERSION).
    "</div>";
    
  }

  /* CHECK IF WOOCOMMERCE IS ACTIVATED */
  public function admin_notice_missing_woocommerce_plugin(){
    deactivate_plugins(plugin_basename(__FILE__));

    if(isset($_GET['activate']) ) 
    {
      unset($_GET['activate']);
    }

    echo "<div class='notice notice-warning is-dismissible>".
    esc_html__("WOOCOMMERCE plugin is missing",SA_PLUGIN_DOMAIN).
        "</div>";
  }
}

/*instantiate SAelementor */
new SAelementor;
?>