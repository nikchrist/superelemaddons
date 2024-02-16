<?php

use \Elementor\Widget_Base;

class RemovePageTitle extends Widget_Base
{
  public function get_name()
  {
    return 'sa_remove_page_title';
  }

  public function get_title()
  {
    return esc_html__('Remove Page Title', SA_PLUGIN_DOMAIN);
  }

  public function get_categories()
  {
    return ['sa_widgets'];
  }

  public function get_icon()
  {
    return 'fa fa-cliboard';
  }

  public function render()
  {
    global $post;
    if ($post) {
      $id = strval($post->ID);
      echo "<style id='rem-title-style'>
            .page-id-$id .entry-title{
            display: none!important;
            }
            </style>";
    }
  }
}
