<?php

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;

class SAproductsGrid extends Widget_Base
{
  public function get_name()
  {
    return 'sa_pr_grid';
  }

  public function get_title()
  {
    return esc_html__('SA Products Grid', SA_PLUGIN_DOMAIN);
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
    return ['productsgrid.js'];
  }

  public function  get_style_depends()
  {
    return ['productsgrid.css'];
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

  public function getProductsByCategory($prcatid)
  {
    $ids = array($prcatid);
    $args = array(
      'post_type'             => array('product', 'product_variation'),
      'post_status'           => 'publish',
      'posts_per_page'        => '8',
      'tax_query'             => array(
        array(
          'taxonomy'      => 'product_cat',
          'field' => 'term_id', //This is optional, as it defaults to 'term_id'
          'terms'         => $ids,
          'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
        )
      )
    );
    $products = get_posts($args); ?>
    <div class="custom-products">
      <?php
      foreach ($products as $product) {
        $probj = wc_get_product($product->ID);
        if ($probj->is_in_stock()) {
          $variation = new WC_Product_Variable($product->ID);
          $link = get_permalink($product->ID);
          $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->ID), 'single-post-thumbnail');
          $price = $probj->get_regular_price();
          $saleprice = $probj->get_sale_price();
          $var_price = $variation->get_variation_regular_price('max', true);
          $var_price_min = $variation->get_variation_regular_price('min', true);
          $var_sale_price = $variation->get_variation_sale_price('min', true);
          $var_sale_price_max = $variation->get_variation_sale_price('max', true);
          $addtocartlink = do_shortcode('[add_to_cart_url id="' . $product->ID . '"]');
      ?>
          <div class="custom-product-item">
            <div class="elem-pr-image">
              <a href="<?php echo $link ?>">
                <img src="<?php echo $image[0]; ?>" data-id="<?php echo $product->ID; ?>">
                <span class="elem-pr-title"><?php echo $product->post_title ?></span>
              </a>
              <?php if ($probj->is_on_sale()) { ?>
                <div class="custom-on-sale-badge">ON SALE</div>
              <?php } ?>
            </div>
            <?php if ($probj->is_type('simple')) { ?>
              <div class="elem-pr-price">
                <?php echo !empty($price) ? $price . '<span class="cur">€</span>' : ''; ?>
                <?php echo !empty($saleprice) ? '--' . $saleprice . '<span class="cur">€</span> ON SALE!' : ''; ?>
              </div>
              <div class="elem-custom-add-to-cart-wrapper">
                <a href="<?php echo $addtocartlink ?>">
                  <button class="elem-custom-add-to-cart">Buy Now!</button>
                </a>
              </div>
            <?php } ?>
            <?php if ($probj->is_type('variable')) {  ?>
              <div class="elem-pr-price">
                <?php
                echo !empty($var_sale_price)  ? $var_sale_price . '<span class="cur">€</span>' : $var_price_min . '<span class="cur">€</span>';
                ?>
                <?php echo !empty($var_sale_price_max) && ($var_sale_price !== $var_sale_price_max) ? '--' . $var_sale_price_max .
                  '<span class="cur">€</span>' : '--' . $var_price . '<span class="cur">€</span>';
                ?>
              </div>

              <div class="elem-custom-add-to-cart-wrapper">
                <a href="<?php echo $link ?>">
                  <button class="elem-custom-add-to-cart">Select Options</button>
                </a>
              </div>
            <?php } ?>
          </div>
      <?php
        }
      } ?>
    </div> <?php
          }

          public function register_controls()
          {

            /* CONTENT */

            $this->start_controls_section(
              'content_section',
              [
                'label' => esc_html__('Products  Grid by Category', SA_PLUGIN_DOMAIN),
              ]
            );

            $this->add_control(
              'sa_pr_grid_choose_category',
              [
                'label' => esc_html__('Choose Category', SA_PLUGIN_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'multiple' => false,
                'options' => $this->getPrCategories()
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
              'sa-products-limit',
              [
                'label' => esc_html__('Limit Products', SA_PLUGIN_DOMAIN),
                'description' => __('The max number of products to show.<br/>Insert -1 or 0 or leave it blank if you want to show all products'),
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
                'label' => 'SA Product Grid Style',
                'tab' =>  Controls_Manager::TAB_STYLE
              ]
            );

            $this->add_control(
              'sa_pr_gridheading_color',
              [
                'label' => esc_html__('Product Grid Heading Color', SA_PLUGIN_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                  '{{WRAPPER}} h3' => 'color: {{VALUE}}',
                ]
              ]
            );

            $this->add_control(
              'sa_prgrid_title_color',
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
                'name' => 'prgrid-title-typography',
                'label' => esc_html__('Grid Title Typography', SA_PLUGIN_DOMAIN),
                'selector' => '{{WRAPPER}} .sa_grid_title h2'
              ]
            );

            $this->add_group_control(
              Group_Control_Typography::get_type(),
              [
                'name' => 'prgrid-titles-typography',
                'label' => esc_html__('product Grid Titles Typography', SA_PLUGIN_DOMAIN),
                'selector' => '{{WRAPPER}} .sa_pr_cat_title h3'
              ]
            );

            $this->add_control(
              'text_pr_grid_align',
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
              'sa_prgrid_text_shadow',
              [
                'label' => esc_html__('Grid Title Text Shadow', SA_PLUGIN_DOMAIN),
                'type' => Controls_Manager::TEXT_SHADOW,
                'selectors' => [
                  '{{WRAPPER}} .sa_grid_title h2' => 'text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};'
                ]
              ]
            );
            $this->add_control(
              'pr_grid_css_class_choice',
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
    <div class="sa_pr_grid_list <?php echo esc_attr($choice) ?>" id="sa_pr_grid">
      <?php
            if (!empty($settings['sa_pr_grid_choose_category'])) {
              $category = get_term_by('name', $settings['sa_pr_grid_choose_category'], 'product_cat');
              $cat_id = $category->term_id;
              $this->getProductsByCategory($cat_id);
            }
            /* foreach ($settings['sa_pr_grid_choose_category'] as $category) {
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
      } */
      ?>
    </div>
<?php
          }
        }

?>