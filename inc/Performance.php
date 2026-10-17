<?php

namespace CS\Theme;

if (! defined('ABSPATH')) {
  exit;
}

class Performance
{
  public function __construct()
  {
    add_action('wp_head', [$this, 'load_critical_css'], 1);

    add_filter('style_loader_tag', [$this, 'preload_styles'], 10, 4);
    add_filter('script_loader_tag', [$this, 'defer_scripts'], 10, 3);
  }

  /**
   * Load critical CSS inline.
   *
   * @return void
   */
  public function load_critical_css(): void
  {
    $critical_css = <<<'CSS'
      :root{
        --content-width:var(--wp--style--global--content-size);
        --color-primary:var(--wp--preset--color--heru-primary);
        --color-secondary:var(--wp--preset--color--heru-secondary);
        --color-black:var(--wp--preset--color--black);
        --color-white:var(--wp--preset--color--white);
      }
      CSS;

    printf(
      '<style id="heru-critical-css">%s</style>',
      $critical_css
    );
  }

  /**
   * Preload selected stylesheets.
   *
   * @param string $html   Stylesheet HTML.
   * @param string $handle Style handle.
   * @param string $href   Stylesheet URL.
   * @param string $media  Media attribute.
   * @return string
   */
  public function preload_styles(
    string $html,
    string $handle,
    string $href,
    string $media
  ): string {
    $preload_styles = [
      'woocommerce-layout',
      'woocommerce-smallscreen',
      'woocommerce-general',
      'woocommerce-blocktheme',
      'wp-components',
      'wpdesk_wc_shipping_notices_ups',
      'flexible-shipping-ups-blocks-integration-frontend',
      'flexible-shipping-ups-blocks-integration-editor',
      'addify_wsp_front_css',
      'wc-blocks-integration',
      'wc-blocks-style',
      'heru-main-0',
      'heru-animations',
    ];

    if (! in_array($handle, $preload_styles, true)) {
      return $html;
    }

    $media_attribute = $media
      ? ' media="' . esc_attr($media) . '"'
      : '';

    return sprintf(
      '<link rel="preload" href="%s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">',
      esc_url($href)
    ) . sprintf(
      '<noscript><link rel="stylesheet" href="%s"%s></noscript>',
      esc_url($href),
      $media_attribute
    );
  }

  /**
   * Defer selected scripts.
   *
   * @param string $tag    Script HTML.
   * @param string $handle Script handle.
   * @param string $src    Script source.
   * @return string
   */
  public function defer_scripts(
    string $tag,
    string $handle,
    string $src
  ): string {
    $defer_scripts = [
      'af_wsp_front_js',
    ];

    if (! in_array($handle, $defer_scripts, true)) {
      return $tag;
    }

    return sprintf(
      '<script src="%s" defer></script>',
      esc_url($src)
    );
  }
}
