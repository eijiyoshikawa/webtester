<?php
/**
 * TECNES Recruit theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TECNES_THEME_VERSION', '1.2.0' );

/**
 * ファイル更新時刻ベースで自動キャッシュバスティング。
 * 静的アセットを編集するたびに ?ver=... が変わるため、
 * ブラウザ／CDN 側のキャッシュを確実に無効化できる。
 */
function tecnes_asset_ver( $relative_path ) {
    $file = get_template_directory() . '/' . ltrim( $relative_path, '/' );
    return file_exists( $file ) ? (string) filemtime( $file ) : TECNES_THEME_VERSION;
}

function tecnes_setup() {
    add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'tecnes_setup' );

function tecnes_enqueue_assets() {
    $theme_uri = get_template_directory_uri();

    wp_enqueue_style(
        'tecnes-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&family=Poppins:wght@300;400;500;600;700&display=swap',
        array(),
        null
    );

    wp_enqueue_style( 'splide', $theme_uri . '/assets/vendor/splide.min.css',        array(), tecnes_asset_ver( 'assets/vendor/splide.min.css' ) );
    wp_enqueue_style( 'swiper', $theme_uri . '/assets/vendor/swiper-bundle.min.css', array(), tecnes_asset_ver( 'assets/vendor/swiper-bundle.min.css' ) );

    wp_enqueue_style( 'tecnes-globals', $theme_uri . '/assets/css/globals.css', array(), tecnes_asset_ver( 'assets/css/globals.css' ) );

    $sections = array(
        'header'      => 'Header.css',
        'gnav'        => 'Gnav.css',
        'mainvisual'  => 'MainVisual.css',
        'read'        => 'Read.css',
        'business'    => 'Business.css',
        'flow'        => 'Flow.css',
        'about'       => 'About.css',
        'discovery'   => 'Discovery.css',
        'people'      => 'People.css',
        'environment' => 'Environment.css',
        'footnav'     => 'FootNav.css',
        'footer'      => 'Footer.css',
    );
    foreach ( $sections as $handle => $file ) {
        wp_enqueue_style( 'tecnes-' . $handle, $theme_uri . '/assets/css/' . $file, array( 'tecnes-globals' ), tecnes_asset_ver( 'assets/css/' . $file ) );
    }

    wp_enqueue_style( 'tecnes-theme-style', get_stylesheet_uri(), array(), tecnes_asset_ver( 'style.css' ) );

    wp_enqueue_script( 'splide', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js', array(), '4.1.4', true );
    wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',              array(), '11.0.0', true );

    foreach ( array(
        'tecnes-header'          => array( 'header.js', array() ),
        'tecnes-mainvisual'      => array( 'mainvisual.js', array( 'splide' ) ),
        'tecnes-business-modal'  => array( 'business-modal.js', array() ),
        'tecnes-people-swiper'   => array( 'people-swiper.js', array( 'swiper' ) ),
        'tecnes-about-counter'   => array( 'about-counter.js', array() ),
        'tecnes-scroll-animator' => array( 'scroll-animator.js', array() ),
    ) as $handle => $cfg ) {
        list( $file, $deps ) = $cfg;
        wp_enqueue_script( $handle, $theme_uri . '/assets/js/' . $file, $deps, tecnes_asset_ver( 'assets/js/' . $file ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'tecnes_enqueue_assets' );

function tecnes_img( $name ) {
    return esc_url( get_template_directory_uri() . '/assets/images/' . ltrim( $name, '/' ) );
}
