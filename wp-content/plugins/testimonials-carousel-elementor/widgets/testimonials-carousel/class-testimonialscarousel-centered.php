<?php
/**
 * TestimonialsCarousel_Centered class.
 *
 * @category   Class
 * @package    TestimonialsCarouselElementor
 * @subpackage WordPress
 * @author     UAPP GROUP
 * @copyright  2024 UAPP GROUP
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link
 * @since      11.3.0
 * php version 7.4.1
 */

namespace TestimonialsCarouselElementor\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

/**
 * TestimonialsCarousel_Centered widget class.
 *
 * @since 11.3.0
 */
class TestimonialsCarousel_Centered extends Widget_Base
{
  /**
   * TestimonialsCarousel_Centered constructor.
   *
   * @param array $data
   * @param null  $args
   *
   * @throws \Exception
   */
  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);
    wp_register_style('swiper', plugins_url('/assets/css/swiper-bundle.min.css', TESTIMONIALS_CAROUSEL_ELEMENTOR), [], TESTIMONIALS_VERSION);
    wp_register_style('testimonials-carousel', plugins_url('/assets/css/testimonials-carousel.min.css', TESTIMONIALS_CAROUSEL_ELEMENTOR), [], TESTIMONIALS_VERSION);
    wp_register_script('swiper', plugins_url('/assets/js/swiper-bundle.min.js', TESTIMONIALS_CAROUSEL_ELEMENTOR), [], TESTIMONIALS_VERSION, true);


    if (!function_exists('get_plugin_data')) {
      require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    if (get_plugin_data(ELEMENTOR__FILE__)['Version'] >= "3.5.0") {
      wp_register_script('testimonials-carousel-widget-handler', plugins_url('/assets/js/testimonials-carousel-widget-handler.min.js', TESTIMONIALS_CAROUSEL_ELEMENTOR), ['elementor-frontend'], TESTIMONIALS_VERSION, true);
    } else {
      wp_register_script('testimonials-carousel-widget-handler', plugins_url('/assets/js/testimonials-carousel-widget-old-elementor-handler.min.js', TESTIMONIALS_CAROUSEL_ELEMENTOR), ['elementor-frontend'], TESTIMONIALS_VERSION, true);
    }
  }

  /**
   * Retrieve the widget name.
   *
   * @return string Widget name.
   * @since  11.3.0
   *
   * @access public
   *
   */
  public function get_name()
  {
    return 'testimonials-carousel-centered';
  }

  /**
   * Retrieve the widget title.
   *
   * @return string Widget title.
   * @since  11.3.0
   *
   * @access public
   *
   */
  public function get_title()
  {
    return __('Testimonial Carousel with Rating Centered', 'testimonials-carousel-elementor');
  }

  /**
   * Retrieve the widget icon.
   *
   * @return string Widget icon.
   * @since  11.3.0
   *
   * @access public
   *
   */
  public function get_icon()
  {
    return 'icon-slider-center-info-icon';
  }

  /**
   * Retrieve the list of categories the widget belongs to.
   *
   * Used to determine where to display the widget in the editor.
   *
   * Note that currently Elementor supports only one category.
   * When multiple categories passed, Elementor uses the first one.
   *
   * @return array Widget categories.
   * @since  11.3.0
   *
   * @access public
   *
   */
  public function get_categories()
  {
    return ['testimonials_carousel'];
  }

  /**
   * Enqueue styles.
   */
  public function get_style_depends()
  {
    $styles = ['swiper', 'testimonials-carousel'];

    return $styles;
  }

  public function get_script_depends()
  {
    $scripts = ['swiper', 'testimonials-carousel-widget-handler', 'ai-btn'];

    return $scripts;
  }

  /**
   * Get default slide.
   *
   * @return array Default slide.
   */
  protected function get_default_slide()
  {
    return [
      'slide_content'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'testimonials-carousel-elementor'),
      'slide_image'     => [
        'url' => Utils::get_placeholder_image_src(),
      ],
      'slide_name'      => __('John Doe', 'testimonials-carousel-elementor'),
      'slide_subtitle'  => __('Manager', 'testimonials-carousel-elementor'),
      'slide_read_more' => __('Read more', 'testimonials-carousel-elementor'),
    ];
  }

  /**
   * Register the widget controls.
   *
   * Adds different input fields to allow the user to change and customize the widget settings.
   *
   * @since  11.3.0
   *
   * @access protected
   */
  protected function _register_controls()
  {
    // Content Section
    $this->start_controls_section(
      'section_content',
      [
        'label' => __('Content', 'testimonials-carousel-elementor'),
      ]
    );

    $api_key          = get_option('elementor_openai_api_key');
    $api_key_validate = get_option('testimonials_openai_validate');

    $repeater = new Repeater();
    $repeater->add_control(
      'slide_show_image',
      [
        'label'        => __('Show Image', 'testimonials-carousel-elementor'),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => __('Show', 'testimonials-carousel-elementor'),
        'label_off'    => __('Hide', 'testimonials-carousel-elementor'),
        'return_value' => 'yes',
        'default'      => 'yes',
      ]
    );
    $repeater->add_control(
      'slide_image',
      [
        'label'     => __('Choose square or round Image', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::MEDIA,
        'default'   => [
          'url' => Utils::get_placeholder_image_src(),
        ],
        'ai'        => [
          'active' => false,
        ],
        'condition' => [
          'slide_show_image' => 'yes',
        ],
      ]
    );
    $repeater->add_control(
      'slide_name',
      [
        'label'              => __('Name', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::TEXT,
        'default'            => __('John Doe', 'testimonials-carousel-elementor'),
        'label_block'        => true,
        'frontend_available' => true,
        'dynamic'            => [
          'active' => true,
        ],
        'ai'                 => [
          'active' => false,
        ],
      ]
    );
    $repeater->add_control(
      'slide_subtitle',
      [
        'label'              => __('Title', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::TEXT,
        'default'            => __('Manager', 'testimonials-carousel-elementor'),
        'label_block'        => true,
        'frontend_available' => true,
        'dynamic'            => [
          'active' => true,
        ],
        'ai'                 => [
          'active' => false,
        ],
      ]
    );
    $repeater->add_control(
      'slide_rating_enable',
      [
        'label'        => __('Rating', 'testimonials-carousel-elementor'),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => __('Show', 'testimonials-carousel-elementor'),
        'label_off'    => __('Hide', 'testimonials-carousel-elementor'),
        'return_value' => 'yes',
        'default'      => 'yes',
      ]
    );
    $repeater->add_control(
      'slide_rating',
      [
        'label'              => __('Rating', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::NUMBER,
        'min'                => 0,
        'max'                => 5,
        'step'               => 1,
        'default'            => 4,
        'frontend_available' => true,
        'condition'          => [
          'slide_rating_enable' => 'yes',
        ],
      ]
    );
    $repeater->add_control(
      'slide_content',
      [
        'label'              => __('Content', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::WYSIWYG,
        'default'            => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'testimonials-carousel-elementor'),
        'frontend_available' => true,
        'dynamic'            => [
          'active' => true,
        ],
        'ai'                 => [
          'active' => false,
        ],
      ]
    );
    $repeater->add_control(
      'slide_read_more',
      [
        'label'              => __('Read more button', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::TEXT,
        'default'            => __('Read more', 'testimonials-carousel-elementor'),
        'label_block'        => true,
        'frontend_available' => true,
        'dynamic'            => [
          'active' => true,
        ],
        'ai'                 => [
          'active' => false,
        ],
      ]
    );

    if (!empty($api_key) && $api_key_validate !== '0') {
      $repeater->add_control(
        'slide_ai_enable',
        [
          'label'        => __('OpenAI', 'testimonials-carousel-elementor'),
          'type'         => Controls_Manager::SWITCHER,
          'label_on'     => __('Show', 'testimonials-carousel-elementor'),
          'label_off'    => __('Hide', 'testimonials-carousel-elementor'),
          'return_value' => 'yes',
          'default'      => 'yes',
        ]
      );

      $repeater->add_control(
        'slide_ai_content',
        [
          'label'              => __('Prompt', 'testimonials-carousel-elementor'),
          'type'               => Controls_Manager::TEXTAREA,
          'default'            => __('Write me a [friendly] review of [500] characters [en] language, from a client of the company [IT soft touch] about [importing cars from the USA]', 'testimonials-carousel-elementor'),
          'frontend_available' => false,
          'rows'               => 20,
          'condition'          => [
            'slide_ai_enable' => 'yes',
          ],
          'ai'                 => [
            'active' => false,
          ],
        ]
      );

      $repeater->add_control(
        'process_ai_button',
        [
          'type'               => Controls_Manager::BUTTON,
          'text'               => __('Process', 'testimonials-carousel-elementor'),
          'frontend_available' => true,
          'dynamic'            => [
            'active' => true,
          ],
          'condition'          => [
            'slide_ai_enable' => 'yes',
          ],
          'event'              => 'send_prompt',
        ]
      );
    }

    $this->add_control(
      'slide',
      [
        'label'              => __('Repeater Slide', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::REPEATER,
        'fields'             => $repeater->get_controls(),
        'title_field'        => 'Slide',
        'frontend_available' => true,
        'default'            => [$this->get_default_slide()],
      ]
    );
    $this->end_controls_section();

    // Global Options Section
    $this->start_controls_section(
      'section_global_options',
      [
        'label' => esc_html__('Global Options', 'testimonials-carousel-elementor'),
      ]
    );

    $this->add_control(
      'slider_global_show_images',
      [
        'label'              => esc_html__('Show Images', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SWITCHER,
        'label_on'           => __('Show', 'testimonials-carousel-elementor'),
        'label_off'          => __('Hide', 'testimonials-carousel-elementor'),
        'return_value'       => 'yes',
        'default'            => 'yes',
        'frontend_available' => true,
      ]
    );

    $this->end_controls_section();

    // Additional Options Section
    $this->start_controls_section(
      'section_additional_options',
      [
        'label' => esc_html__('Additional Options', 'testimonials-carousel-elementor'),
      ]
    );

    $slides_to_show = range(1, 4);
    $slides_to_show = array_combine($slides_to_show, $slides_to_show);

    $this->add_responsive_control(
      'slides_to_show',
      [
        'label'              => esc_html__('Slides to show', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SELECT,
        'options'            => [
            '' => esc_html__('Default', 'testimonials-carousel-elementor'),
          ] + $slides_to_show,
        'frontend_available' => true,
      ]
    );

    $this->add_responsive_control(
      'slides_to_scroll',
      [
        'label'              => esc_html__('Slides to scroll', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SELECT,
        'description'        => esc_html__('Set how many slides are scrolled per swipe.', 'testimonials-carousel-elementor'),
        'options'            => [
          ''  => esc_html__('Default', 'testimonials-carousel-elementor'),
          '1' => esc_html__(1, 'testimonials-carousel-elementor'),
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'slider_loop',
      [
        'label'              => esc_html__('Loop', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SWITCHER,
        'label_on'           => __('Yes', 'testimonials-carousel-elementor'),
        'label_off'          => __('No', 'testimonials-carousel-elementor'),
        'return_value'       => 'yes',
        'default'            => 'yes',
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'show_line_text',
      [
        'label'              => esc_html__('Show Lines With Text', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::NUMBER,
        'min'                => 1,
        'max'                => 21,
        'step'               => 1,
        'default'            => 7,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'autoplay',
      [
        'label'              => esc_html__('Autoplay', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SELECT,
        'default'            => 'yes',
        'options'            => [
          'yes' => esc_html__('Yes', 'testimonials-carousel-elementor'),
          'no'  => esc_html__('No', 'testimonials-carousel-elementor'),
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'autoplay_speed',
      [
        'label'              => esc_html__('Autoplay speed', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::NUMBER,
        'default'            => 5000,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'navigation',
      [
        'label'              => esc_html__('Navigation', 'testimonials-carousel-elementor'),
        'type'               => Controls_Manager::SELECT,
        'default'            => 'dots',
        'options'            => [
          'both'   => esc_html__('Arrows and Dots', 'testimonials-carousel-elementor'),
          'arrows' => esc_html__('Arrows', 'testimonials-carousel-elementor'),
          'dots'   => esc_html__('Dots', 'testimonials-carousel-elementor'),
          'none'   => esc_html__('None', 'testimonials-carousel-elementor'),
        ],
        'frontend_available' => true,
      ]
    );

    $this->end_controls_section();

    // General styles Section
    $this->start_controls_section(
      'general_styles_section',
      [
        'label' => esc_html__('General styles', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_group_control(
      Group_Control_Background::get_type(),
      [
        'name'     => 'background',
        'types'    => ['classic', 'gradient'],
        'selector' => '{{WRAPPER}} .swiper-slide .slider-container-block-background',
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'slider_border',
        'selector' => '{{WRAPPER}} .swiper-slide .slider-container-block-background',
      ]
    );

    $this->add_responsive_control(
      'slider_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .swiper-slide .slider-container-block-background' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'slider_box_shadow',
        'selector' => '{{WRAPPER}} .swiper-slide .slider-container-block-background',
      ]
    );

    $this->add_responsive_control(
      'icon_size',
      [
        'label'     => esc_html__('Rating icon size', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 5,
            'max' => 30,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .slide-icons i' => 'font-size: {{SIZE}}{{UNIT}}',
        ],
        'separator' => 'before',
      ]
    );

    $this->add_responsive_control(
      'icon_space',
      [
        'label'     => esc_html__('Rating icon spacing', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 20,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .slide-icons i' => 'margin-right: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_control(
      'stars_color',
      [
        'label'     => esc_html__('Rating icon color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .slide-icons .icon-star-full' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'stars_unmarked_color',
      [
        'label'     => esc_html__('Rating unmarked icon color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .slide-icons .icon-star-empty' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->end_controls_section();

    // Images style Section
    $this->start_controls_section(
      'images_styles_section',
      [
        'label' => esc_html__('Images styles', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_responsive_control(
      'slider_image_align',
      [
        'label'     => esc_html__('Alignment Images', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .slide-block' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'slider_image_position',
      [
        'label'     => esc_html__('Images Position', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'column'         => [
            'title' => esc_html__('Column', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-v-align-top',
          ],
          'column-reverse' => [
            'title' => esc_html__('Column-Reverse', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-v-align-bottom',
          ],
        ],
        'default'   => 'column',
        'selectors' => [
          '{{WRAPPER}} .slide-block' => 'flex-direction: {{VALUE}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'slider_width_block_image',
      [
        'label'      => esc_html__('Width Block Image', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'custom'],
        'default'    => [
          'unit' => '%',
        ],
        'range'      => [
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .slide-image' => 'width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'slider_width_images',
      [
        'label'      => esc_html__('Width Images', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'custom'],
        'default'    => [
          'unit' => '%',
        ],
        'range'      => [
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .slide-image img' => 'width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'slider_height_images',
      [
        'label'      => esc_html__('Height Images', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'custom'],
        'default'    => [
          'unit' => '%',
        ],
        'range'      => [
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .slide-image img' => 'height: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'slider_image_border',
        'selector' => '{{WRAPPER}} .slide-image img',
      ]
    );

    $this->add_responsive_control(
      'slider_image_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .slide-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'slider_image_box_shadow',
        'selector' => '{{WRAPPER}} .slide-image img',
      ]
    );
    $this->end_controls_section();

    // Title style Section
    $this->start_controls_section(
      'title_styles_section',
      [
        'label' => esc_html__('Title styles', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_responsive_control(
      'slider_name_align',
      [
        'label'     => esc_html__('Alignment Name', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .slide-title' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'slider_title_align',
      [
        'label'     => esc_html__('Alignment Title', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .slide-subtitle' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'slider_width_block_title',
      [
        'label'      => esc_html__('Width Block Title', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'custom'],
        'default'    => [
          'unit' => '%',
        ],
        'range'      => [
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .slide-basic-info' => 'width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'slider_title_space',
      [
        'label'     => esc_html__('Spacing', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 40,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .slide-basic-info' => 'gap: {{SIZE}}{{UNIT}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'slider_rating_align',
      [
        'label'     => esc_html__('Alignment Rating', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'center',
        'selectors' => [
          '{{WRAPPER}} .slide-icons' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->end_controls_section();

    // Slider styles Section
    $this->start_controls_section(
      'slider_styles_section',
      [
        'label' => __('Slider styles', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_control(
      'slider_name_color',
      [
        'label'     => esc_html__('Name color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .swiper-wrapper .slide-title' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'slider_name_typography',
        'label'    => esc_html__('Name typography', 'testimonials-carousel-elementor'),
        'selector' => '{{WRAPPER}} .swiper-wrapper .slide-title',
      ]
    );
    $this->add_control(
      'slider_title_color',
      [
        'label'     => esc_html__('Title color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .swiper-wrapper .slide-subtitle' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'slider_title_typography',
        'label'    => esc_html__('Title typography', 'testimonials-carousel-elementor'),
        'selector' => '{{WRAPPER}} .swiper-wrapper .slide-subtitle',
      ]
    );
    $this->add_control(
      'slider_content_color',
      [
        'label'     => esc_html__('Content color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .swiper-wrapper .slide-description' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'slider_content_typography',
        'label'    => esc_html__('Content typography', 'testimonials-carousel-elementor'),
        'selector' => '{{WRAPPER}} .swiper-wrapper .slide-description',
      ]
    );
    $this->add_responsive_control(
      'slider_content_align',
      [
        'label'     => esc_html__('Alignment Content', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'    => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center'  => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'   => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__('Justify', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-justify',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .slide-description' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'slider_read_more_color',
      [
        'label'     => esc_html__('Read more button color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .swiper-wrapper .slide-read-more' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'slider_read_more_typography',
        'label'    => esc_html__('Read more button typography', 'testimonials-carousel-elementor'),
        'selector' => '{{WRAPPER}} .swiper-wrapper .slide-read-more',
      ]
    );
    $this->add_responsive_control(
      'slider_read_align',
      [
        'label'     => esc_html__('Alignment Button', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'center',
        'selectors' => [
          '{{WRAPPER}} .slide-read-more' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->end_controls_section();

    // Popup styles Section
    $this->start_controls_section(
      'popup_styles_section',
      [
        'label' => __('Popup styles', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_group_control(
      Group_Control_Background::get_type(),
      [
        'name'     => 'popup_background',
        'types'    => ['classic', 'gradient'],
        'selector' => '{{WRAPPER}} .slider-modal .slider-modal-container.slider-container-block-background',
      ]
    );
    $this->add_responsive_control(
      'popup_header_space',
      [
        'label'     => esc_html__('Head spacing', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 140,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-block' => 'gap: {{SIZE}}{{UNIT}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'popup_border',
        'selector' => '{{WRAPPER}} .slider-modal .slider-modal-container.slider-container-block-background',
      ]
    );

    $this->add_responsive_control(
      'popup_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .slider-modal .slider-modal-container.slider-container-block-background' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'popup_box_shadow',
        'selector' => '{{WRAPPER}} .slider-modal .slider-modal-container.slider-container-block-background',
      ]
    );

    $this->add_responsive_control(
      'popup_icon_size',
      [
        'label'     => esc_html__('Rating icon size', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 5,
            'max' => 30,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-icons i' => 'font-size: {{SIZE}}{{UNIT}}',
        ],
        'separator' => 'before',
      ]
    );

    $this->add_responsive_control(
      'popup_icon_space',
      [
        'label'     => esc_html__('Rating icon spacing', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 20,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-icons i' => 'margin-right: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_control(
      'popup_stars_color',
      [
        'label'     => esc_html__('Rating icon color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-icons .icon-star-full' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'popup_stars_unmarked_color',
      [
        'label'     => esc_html__('Rating unmarked icon color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-icons .icon-star-empty' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'popup_name_color',
      [
        'label'     => esc_html__('Name color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .slider-modal-container .slide-title' => 'color: {{VALUE}};',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'popup_name_typography',
        'label'    => esc_html__('Name typography', 'testimonials-carousel-elementor'),
        'selector' => '{{WRAPPER}} .slider-modal-container .slide-title',
      ]
    );
    $this->add_responsive_control(
      'popup_name_align',
      [
        'label'     => esc_html__('Alignment Name', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-title' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'popup_title_color',
      [
        'label'     => esc_html__('Title color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .slider-modal-container .slide-subtitle' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'popup_title_typography',
        'label'    => esc_html__('Title typography', 'testimonials-carousel-elementor'),
        'selector' => '{{WRAPPER}} .slider-modal-container .slide-subtitle',
      ]
    );
    $this->add_responsive_control(
      'popup_title_align',
      [
        'label'     => esc_html__('Alignment Title', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-subtitle' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'popup_content_color',
      [
        'label'     => esc_html__('Content color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .slider-modal-container .slide-description' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'popup_content_typography',
        'label'    => esc_html__('Content typography', 'testimonials-carousel-elementor'),
        'selector' => '{{WRAPPER}} .slider-modal-container .slide-description',
      ]
    );
    $this->add_responsive_control(
      'popup_content_align',
      [
        'label'     => esc_html__('Alignment Content', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'    => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center'  => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'   => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__('Justify', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-justify',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .slider-modal-container .slide-description' => 'text-align: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'popup_image_align',
      [
        'label'     => esc_html__('Alignment Images', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-block' => 'text-align: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_responsive_control(
      'popup_image_position',
      [
        'label'     => esc_html__('Images Position', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'column'         => [
            'title' => esc_html__('Column', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-v-align-top',
          ],
          'column-reverse' => [
            'title' => esc_html__('Column-Reverse', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-v-align-bottom',
          ],
        ],
        'default'   => 'column',
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-block' => 'flex-direction: {{VALUE}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'popup_width_block_image',
      [
        'label'      => esc_html__('Width Block Image', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'custom'],
        'default'    => [
          'unit' => '%',
        ],
        'range'      => [
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .slider-modal .slide-image' => 'width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'popup_width_images',
      [
        'label'      => esc_html__('Width Images', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'custom'],
        'default'    => [
          'unit' => '%',
        ],
        'range'      => [
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .slider-modal .slide-image img' => 'width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'popup_height_images',
      [
        'label'      => esc_html__('Height Images', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'custom'],
        'default'    => [
          'unit' => '%',
        ],
        'range'      => [
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .slider-modal .slide-image img' => 'height: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'popup_image_border',
        'selector' => '{{WRAPPER}} .slider-modal .slide-image img',
      ]
    );

    $this->add_responsive_control(
      'popup_image_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .slider-modal .slide-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'popup_image_box_shadow',
        'selector' => '{{WRAPPER}} .slider-modal .slide-image img',
      ]
    );

    $this->add_responsive_control(
      'popup_width_block_title',
      [
        'label'      => esc_html__('Width Block Title', 'testimonials-carousel-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'custom'],
        'default'    => [
          'unit' => '%',
        ],
        'range'      => [
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .slider-modal .slide-basic-info'              => 'width: {{SIZE}}{{UNIT}}',
          '{{WRAPPER}} .slider-modal .slide-basic-info .slide-icons' => 'width: {{SIZE}}{{UNIT}}',
          '{{WRAPPER}} .slider-modal .slide-info'                    => 'width: {{SIZE}}{{UNIT}}',
        ],
        'separator'  => 'before',
      ]
    );
    $this->add_responsive_control(
      'popup_title_space',
      [
        'label'     => esc_html__('Spacing', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 40,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-basic-info' => 'gap: {{SIZE}}{{UNIT}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'popup_rating_align',
      [
        'label'     => esc_html__('Alignment Rating', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'testimonials-carousel-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'center',
        'selectors' => [
          '{{WRAPPER}} .slider-modal .slide-icons' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->end_controls_section();

    // Navigation styles Section
    $this->start_controls_section(
      'navigation_styles_section',
      [
        'label' => __('Navigation styles', 'testimonials-carousel-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_control(
      'heading_style_arrows',
      [
        'label'     => esc_html__('Arrows', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'arrows_size',
      [
        'label'     => esc_html__('Arrows size', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 30,
            'max' => 60,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .mySwiper .swiper-button-prev, {{WRAPPER}} .mySwiper .swiper-button-next'             => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .mySwiper .swiper-button-prev:after, {{WRAPPER}} .mySwiper .swiper-button-next:after' => 'font-size: calc({{SIZE}}{{UNIT}} / 3);',
        ],
      ]
    );

    $this->add_control(
      'arrows_color',
      [
        'label'     => esc_html__('Arrows color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .mySwiper .swiper-button-prev:after, {{WRAPPER}} .mySwiper .swiper-button-next:after' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'arrows_hover_color',
      [
        'label'     => esc_html__('Arrows hover color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .mySwiper .swiper-button-prev:hover::after, {{WRAPPER}} .mySwiper .swiper-button-next:hover::after' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'heading_style_dots',
      [
        'label'     => esc_html__('Pagination', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'dots_size',
      [
        'label'     => esc_html__('Dots size', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 5,
            'max' => 12,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .mySwiper .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'active_dot_size',
      [
        'label'     => esc_html__('Active dot size', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 5,
            'max' => 12,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .mySwiper .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'dots_inactive_color',
      [
        'label'     => esc_html__('Dots color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .mySwiper .swiper-pagination-bullet' => 'background: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'dots_inactive_hover_color',
      [
        'label'     => esc_html__('Dots hover color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .mySwiper .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'active_dot_color',
      [
        'label'     => esc_html__('Active dot color', 'testimonials-carousel-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .mySwiper .swiper-rating-centered-buttons-block .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
        ],
      ]
    );
    $this->end_controls_section();
  }

  /**
   * Render the widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since  11.3.0
   *
   * @access protected
   */
  protected function render()
  {
    $settings = $this->get_settings_for_display();
    if (get_plugin_data(ELEMENTOR__FILE__)['Version'] < "3.5.0") {
      $this->add_render_attribute(
        'my_swiper',
        [
          'class'                               => ['slider-params'],
          'data-slidestoshow-myswiper'          => esc_attr($settings['slides_to_show']),
          'data-slidestoshow-myswiper-tablet'   => esc_attr($settings['slides_to_show_tablet']),
          'data-slidestoshow-myswiper-mobile'   => esc_attr($settings['slides_to_show_mobile']),
          'data-slidestoscroll-myswiper'        => esc_attr($settings['slides_to_scroll']),
          'data-slidestoscroll-myswiper-tablet' => esc_attr($settings['slides_to_scroll_tablet']),
          'data-slidestoscroll-myswiper-mobile' => esc_attr($settings['slides_to_scroll_mobile']),
          'data-navigation-myswiper'            => esc_attr($settings['navigation']),
          'data-autoplay-myswiper'              => esc_attr($settings['autoplay']),
          'data-autoplayspeed-myswiper'         => esc_attr($settings['autoplay_speed']),
          'data-sliderloop-myswiper'            => esc_attr($settings['slider_loop']),
          'data-showlinetext-myswiper'          => esc_attr($settings['show_line_text']),
        ]
      );
    }

    if ($settings['slide']) {
      if (get_plugin_data(ELEMENTOR__FILE__)['Version'] < "3.5.0") { ?>
        <div <?php echo $this->get_render_attribute_string('my_swiper'); ?>></div>
      <?php } ?>

      <section class="swiper mySwiper myTestimonials mySwiperCentered <?php if (
        esc_attr($settings['navigation']) === "dots"
        || esc_attr($settings['navigation']) === "none"
      ) { ?>slider-arrows-disabled<?php } ?>">
        <ul class="swiper-wrapper">
          <?php
          foreach ($settings['slide'] as $item) { ?>
            <li class="swiper-slide slider-container-background">
              <div class="block-shadow slider-container-block-background">
                <div class="slide-block">
                  <?php if (esc_url($item['slide_image']['url']) && $item['slide_show_image'] === 'yes' && $settings['slider_global_show_images'] === 'yes') { ?>
                    <div class="slide-image">
                      <img src="<?php echo esc_url($item['slide_image']['url']) ?>" alt="Slide Image">
                    </div>
                  <?php } ?>
                  <div class="slide-basic-info">
                    <?php if ($item['slide_rating_enable'] === 'yes') { ?>
                      <div class="slide-icons">
                        <?php
                        for ($i = 0; $i < $item['slide_rating']; $i++) { ?>
                          <i class="icon-star-full"></i>
                        <?php }
                        for ($i = 0; $i < (5 - $item['slide_rating']); $i++) { ?>
                          <i class="icon-star-empty"></i>
                        <?php } ?>
                      </div>
                    <?php } ?>
                    <div class="slide-info">
                      <span class="slide-title"><?php echo wp_kses($item['slide_name'], []); ?></span>
                      <span class="slide-subtitle"><?php echo wp_kses($item['slide_subtitle'], []); ?></span>
                    </div>
                  </div>
                </div>
                <div class="slide-content">
                  <div class="slide-description"
                       style="line-height: 22px;-webkit-line-clamp: <?php echo esc_attr($settings['show_line_text']); ?>"><?php echo wp_kses_post($item['slide_content']); ?></div>
                  <div class="slide-read-more"><?php echo wp_kses($item['slide_read_more'], []); ?></div>
                </div>
              </div>
            </li>
          <?php } ?>
        </ul>
        <div class="swiper-rating-centered-buttons-block">
          <div class="swiper-button-prev swiper-rating-centered-button-prev"></div>
          <div class="swiper-pagination swiper-rating-centered-pagination"></div>
          <div class="swiper-button-next swiper-rating-centered-button-next"></div>
        </div>
      </section>
      <div class="slider-modal slider-rating-centered-modal" id="slider-modal">
        <div class="slider-modal-bg slider-modal-exit"></div>
        <div class="slider-modal-container slider-container-background slider-container-block-background">
          <div class="slider-modal-container-info"></div>
          <button class="slider-modal-close slider-modal-exit icon-close"></button>
        </div>
      </div>
    <?php } ?>
  <?php }
}
