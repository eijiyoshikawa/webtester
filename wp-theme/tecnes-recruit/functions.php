<?php
/**
 * TECNES Recruit theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TECNES_THEME_VERSION', '1.3.0' );

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

/**
 * Customizer 設定値の取得（デフォルト付き）。
 * 管理画面: 外観 → カスタマイズ → 会社情報 / 採用エントリーリンク から編集可能。
 */
function tecnes_opt( $key, $default = '' ) {
    return get_theme_mod( 'tecnes_' . $key, $default );
}

/**
 * カスタマイザー登録
 */
function tecnes_customize_register( $wp_customize ) {
    // ===== 会社情報 =====
    $wp_customize->add_section( 'tecnes_company', array(
        'title'    => __( '会社情報', 'tecnes-recruit' ),
        'priority' => 30,
    ) );

    $fields = array(
        'company_en'    => array( 'TECNES Inc.', '英語社名（著作権表記等で使用）' ),
        'founded_date'  => array( '', '創立（例: 2010年）' ),
        'capital'       => array( '', '資本金（例: 10,000,000円）' ),
        'postal_code'   => array( '', '郵便番号（例: 〒000-0000）' ),
        'address'       => array( '', '住所' ),
        'map_url'       => array( '', 'Googleマップ URL' ),
        'tel'           => array( '', '電話番号（例: 00-0000-0000）' ),
        'station_1'     => array( '', '最寄り駅 1（例: ○○線「○○駅」より 徒歩○分）' ),
        'station_2'     => array( '', '最寄り駅 2' ),
        'station_3'     => array( '', '最寄り駅 3' ),
    );
    foreach ( $fields as $key => $cfg ) {
        list( $default, $label ) = $cfg;
        $wp_customize->add_setting( 'tecnes_' . $key, array(
            'default'           => $default,
            'sanitize_callback' => 'wp_kses_post',
        ) );
        $wp_customize->add_control( 'tecnes_' . $key, array(
            'label'   => $label,
            'section' => 'tecnes_company',
            'type'    => 'text',
        ) );
    }

    // ===== 採用エントリー外部リンク =====
    $wp_customize->add_section( 'tecnes_entry_links', array(
        'title'    => __( '採用エントリーリンク', 'tecnes-recruit' ),
        'priority' => 31,
    ) );

    $links = array(
        'mynavi_url'      => 'マイナビ エントリー URL',
        'careertasu_url'  => 'キャリタス エントリー URL',
        'corp_site_url'   => 'コーポレートサイト URL',
        'privacy_url'     => 'プライバシーポリシー URL（空ならテーマ内 /privacy/ ）',
    );
    foreach ( $links as $key => $label ) {
        $wp_customize->add_setting( 'tecnes_' . $key, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'tecnes_' . $key, array(
            'label'   => $label,
            'section' => 'tecnes_entry_links',
            'type'    => 'url',
        ) );
    }
}
add_action( 'customize_register', 'tecnes_customize_register' );

/**
 * エントリー（マイナビ）リンク。未設定なら #entry にフォールバック。
 */
function tecnes_mynavi_url() {
    $u = tecnes_opt( 'mynavi_url' );
    return $u ? esc_url( $u ) : '#entry';
}
function tecnes_careertasu_url() {
    $u = tecnes_opt( 'careertasu_url' );
    return $u ? esc_url( $u ) : '#entry';
}
