<?php

use Elementor\Plugin;
// We check if the Elementor plugin has been installed / activated.
if (!in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))) {
  return;
}

class SAwidgets
{
  private static $_instance;

  public static function getinstance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self;
    }

    return self::$_instance;
  }

  public function init()
  {
    add_action('elementor/widgets/register', array($this, 'sa_register_widgets'));
    add_action('elementor/frontend/after_register_scripts', array($this, 'sa_register_scripts'));
    add_action('elementor/frontend/after_register_styles', array($this, 'sa_register_styles'));
    add_action('elementor/elements/categories_registered', array($this, 'sa_register_categories'));
  }

  public function sa_register_widgets($widgets_manager)
  {
    foreach (glob(SA_PLUGIN_PATH . "elementor/widgets/*.php") as $file) {
      require $file;
    }

    $customwidgetslist = [
      'SAproductCategoryGrid',
      'RemovePageTitle',
      'SAproductsGrid'
    ];
    foreach ($customwidgetslist as $customwidget) {
      $widgets_manager->register(new $customwidget);
    }
  }

  public function sa_register_scripts()
  {
    // Check if the specific widget is active
    if (is_home() || is_front_page()) {
      wp_enqueue_script(SA_PLUGIN_DOMAIN . "-scripts", SA_PLUGIN_URL . 'elementor/assets/main.js', array('jquery'), true);
      wp_enqueue_script(SA_PLUGIN_DOMAIN . "-scriptspr", SA_PLUGIN_URL . 'elementor/assets/productsgrid.js', array('jquery'), true);
    }
  }

  public function sa_register_styles()
  {
    if (is_home() || is_front_page()) {
      wp_enqueue_style(SA_PLUGIN_DOMAIN . "-styles", SA_PLUGIN_URL . 'elementor/assets/main.css');
      wp_enqueue_style(SA_PLUGIN_DOMAIN . "-stylespr", SA_PLUGIN_URL . 'elementor/assets/productsgrid.css');
    }
  }

  public function sa_register_categories($elements_manager)
  {
    $elements_manager->add_category(
      'sa_widgets',
      [
        'title' => esc_html__('Super Elementor Widgets', SA_PLUGIN_DOMAIN),
        'icon'  => 'fa fa-plug'
      ]
    );
  }
}

SAwidgets::getInstance()->init();
