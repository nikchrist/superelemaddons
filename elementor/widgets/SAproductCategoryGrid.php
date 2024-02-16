<?php

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;

class SAproductCategoryGrid extends Widget_Base
{
  public function get_name()
  {
    return 'sa_pr_cat_grid';
  }

  public function get_title()
  {
    return esc_html__('SA Product Categories Grid', SA_PLUGIN_DOMAIN);
  }

  public function get_categories()
  {
    return ['sa_widgets'];
  }

  public function get_icon()
  {
    return 'fa fa-cliboard';
  }

  public function  get_script_depends()
  {
    return ['main.js'];
  }

  public function  get_style_depends()
  {
    return ['main.css'];
  }

  public function  getPrCategories(): array
  {
    $categorylist = [];
    $args = [
      'taxonomy' => 'product_cat',
      'limit' => -1,
      'hide_empty' => false
    ];

    $allprcats = get_terms($args);

    foreach ($allprcats as $cat) {
      $categorylist["$cat->name"] = $cat->name;
    }

    return $categorylist;
  }

  public function getCategoryByName($prcat)
  {

    $thumbnailid = get_term_meta($prcat->term_id, 'thumbnail_id', true); // get the id of the image of the category
    $image = wp_get_attachment_url($thumbnailid); // get image url
    $prcatlink = get_term_link($prcat->term_id, 'product_cat'); // get category url
?>
    <div class="sa_pr_cat_grid_list_item">
      <a href="<?php echo $prcatlink ?>">
        <img src="<?php echo $image ?>" class="sa_pr_cat_img" alt="<?php echo $prcat->name ?>" />
        <div class="sa_pr_cat_title">
          <h3><?php echo $prcat->name ?></h3>
        </div>
      </a>
    </div>
    <?php

  }

  public function getAllCategories($limit)
  {
    $args = [
      'taxonomy' => 'product_cat',
      'number' => $limit,
      'hide_empty' => false
    ];
    $allcategories  = get_terms($args);

    foreach ($allcategories as $prcat) {
      $thumbnailid = get_term_meta($prcat->term_id, 'thumbnail_id', true); // get the id of the image of the category
      $image = wp_get_attachment_url($thumbnailid); // get image url
      $prcatlink = get_term_link($prcat->term_id, 'product_cat'); // get category url
    ?>
      <div class="sa_pr_cat_grid_list_item">
        <a href="<?php echo $prcatlink ?>">
          <img src="<?php echo $image ?>" class="sa_pr_cat_img" alt="<?php echo $prcat->name ?>" />
          <div class="sa_pr_cat_title">
            <h3><?php echo $prcat->name ?></h3>
          </div>
        </a>
      </div>
    <?php
    }
  }

  public function register_controls()
  {

    /* CONTENT */

    $this->start_controls_section(
      'content_section',
      [
        'label' => esc_html__('Product Categories Grid', SA_PLUGIN_DOMAIN),
      ]
    );

    $this->add_control(
      'sa_pr_cat_grid_choose_categories',
      [
        'label' => esc_html__('Choose Product Categories', SA_PLUGIN_DOMAIN),
        'type' => Controls_Manager::SELECT2,
        'label_block' => true,
        'multiple' => true,
        'options' => ['ALL' => 'ALL'] + $this->getPrCategories(),
        'default' => ['ALL']
      ]
    );

    $this->add_control(
      'sa_pr_cat_grid_general_title',
      [
        'label' => esc_html__('Grid Title', SA_PLUGIN_DOMAIN),
        'type' => Controls_Manager::TEXT
      ]
    );

    $this->add_control(
      'sa-limit',
      [
        'label' => esc_html__('Limit Product Categories', SA_PLUGIN_DOMAIN),
        'description' => __('The max number of categories to show.<br/>Insert -1 or 0 or leave it blank if you want to show all categories
        <br/>You must select ALL in Choose Products Categories in order for limit to work'),
        'type' => Controls_Manager::NUMBER,
        'step' => 1,
        'default' => 0,
      ]
    );


    $this->end_controls_section();

    /*END OF CONTENT */

    /* STYLE  */

    $this->start_controls_section(
      'style_section',
      [
        'label' => 'SA Product Categories Style',
        'tab' =>  Controls_Manager::TAB_STYLE
      ]
    );

    $this->add_control(
      'sa_pr_cat_grid_categoryheading_color',
      [
        'label' => esc_html__('Categories Heading Color', SA_PLUGIN_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} h3' => 'color: {{VALUE}}',
        ]
      ]
    );

    $this->add_control(
      'sa_pr_cat_grid_title_color',
      [
        'label' => esc_html__('Grid Title Color', SA_PLUGIN_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .sa_grid_title h2' => 'color: {{VALUE}}',
        ]
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'grid-title-typography',
        'label' => esc_html__('GRID TITLE TYPOGRAPHY', SA_PLUGIN_DOMAIN),
        'selector' => '{{WRAPPER}} .sa_grid_title h2'
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'cats-titles-typography',
        'label' => esc_html__('Categories Titles TYPOGRAPHY', SA_PLUGIN_DOMAIN),
        'selector' => '{{WRAPPER}} .sa_pr_cat_title h3'
      ]
    );

    $this->add_control(
      'text_align',
      [
        'label' => esc_html__('Grid Title Alignment', SA_PLUGIN_DOMAIN),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => esc_html__('Left', SA_PLUGIN_DOMAIN),
            'icon' => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', SA_PLUGIN_DOMAIN),
            'icon' => 'eicon-text-align-center',
          ],
          'right' => [
            'title' => esc_html__('Right', SA_PLUGIN_DOMAIN),
            'icon' => 'eicon-text-align-right',
          ],
        ],
        'default' => 'center',
        'toggle' => true,
        'selectors' => [
          '{{WRAPPER}} .sa_grid_title h2' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'sa_text_shadow',
      [
        'label' => esc_html__('Grid Title Text Shadow', SA_PLUGIN_DOMAIN),
        'type' => Controls_Manager::TEXT_SHADOW,
        'selectors' => [
          '{{WRAPPER}} .sa_grid_title h2' => 'text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};'
        ]
      ]
    );
    $this->add_control(
      'css_class_choice',
      [
        'label'   => 'Choose grid or carousel',
        'type'    => Controls_Manager::SELECT,
        'default' => 'grid',
        'options' => [
          'grid' => 'grid',
          'carousel' => 'carousel',
        ],
      ]
    );
    $this->end_controls_section();

    /* END OF STYLE */
  }

  public function render()
  {

    $settings = $this->get_settings_for_display();
    $choice = isset($settings['css_class_choice']) ? $settings['css_class_choice'] : "";
    ?>
    <div class="sa_grid_title">
      <h2><?php echo $settings['sa_pr_cat_grid_general_title'] ?></h2>
    </div>
    <div class="sa_pr_cat_grid_list <?php echo esc_attr($choice) ?>" id="sa_carousel_grid">
      <?php if ($choice == "carousel") { ?>
        <div class="carousel-nav">
          <div id="next">
            &rarr;
          </div>
          <div id="prev">
            &larr;
          </div>
        </div>
      <?php
      }
      foreach ($settings['sa_pr_cat_grid_choose_categories'] as $category) {
        if ($category == 0) {
          return;
        }

        if ($category == 'ALL') {
          $this->getAllCategories($settings['sa-limit']);
          return;
        } else {
          $prcat = get_term_by('name', $category, 'product_cat'); //get categories by their names
          $this->getCategoryByName($prcat);
        }
      }
      ?>
    </div>
<?php
  }
}

?>