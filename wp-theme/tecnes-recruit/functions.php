<?php
/**
 * TECNES Recruit theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TECNES_THEME_VERSION', '1.5.0' );

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
 * コンテンツ画像スロット。
 * Customizer で「コンテンツ画像」セクションから個別にメディアライブラリ画像を割り当て可能。
 * 未設定なら $fallback_filename のテーマ同梱画像を返す。
 *
 * これにより:
 * - サーバー／CDN がテーマフォルダ内画像を強くキャッシュしていても、
 *   メディアライブラリ画像 (uploads/) は別の URL なのでキャッシュを回避可能
 * - WP 管理画面から画像差し替えが可能（FTP / ZIP 再アップ不要）
 */
function tecnes_image( $slot_key, $fallback_filename = null ) {
    $url = get_theme_mod( 'tecnes_image_' . $slot_key );
    if ( $url ) {
        return esc_url( $url );
    }
    return $fallback_filename ? tecnes_img( $fallback_filename ) : '';
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
 * コンテンツ画像セクション登録ヘルパー
 */
function tecnes_register_image_section( $wp_customize, $section_id, $title, $priority, $slots ) {
    $wp_customize->add_section( $section_id, array(
        'title'    => $title,
        'priority' => $priority,
    ) );
    foreach ( $slots as $slot_key => $label ) {
        $setting_id = 'tecnes_image_' . $slot_key;
        $wp_customize->add_setting( $setting_id, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control(
            $wp_customize,
            $setting_id,
            array(
                'label'       => $label,
                'section'     => $section_id,
                'description' => __( '空欄ならテーマ同梱のフォールバック画像が表示されます。', 'tecnes-recruit' ),
            )
        ) );
    }
}

/**
 * コンテンツ画像 Customizer 登録（カスタマイザー登録の続き）
 */
add_action( 'customize_register', function ( $wp_customize ) {
    // ===== Main Visual スライダー =====
    tecnes_register_image_section( $wp_customize, 'tecnes_imgs_mv', 'MV スライダー画像', 40, array(
        'mv_slide_1' => 'MV スライド 1',
        'mv_slide_2' => 'MV スライド 2',
        'mv_slide_3' => 'MV スライド 3',
        'mv_slide_4' => 'MV スライド 4',
    ) );

    // ===== Section main images =====
    tecnes_register_image_section( $wp_customize, 'tecnes_imgs_sections', 'セクションメイン画像', 41, array(
        'read_main'        => 'READ セクション 右側画像',
        'trading_card'     => '商社のしごとカード 画像',
        'manufacturing_card' => 'モノづくりのしごとカード 画像',
        'about_main'       => 'ABOUT セクション 下部画像',
        'discovery_bg'     => 'DISCOVERY セクション 背景画像',
        'people_main'      => 'PEOPLE セクション メイン画像',
    ) );

    // ===== People カード =====
    tecnes_register_image_section( $wp_customize, 'tecnes_imgs_people', 'PEOPLE 社員サムネイル', 42, array(
        'people_1' => '社員 1',
        'people_2' => '社員 2',
        'people_3' => '社員 3',
        'people_4' => '社員 4',
        'people_5' => '社員 5',
        'people_6' => '社員 6',
        'people_7' => '社員 7',
    ) );

    // ===== Business モーダル =====
    tecnes_register_image_section( $wp_customize, 'tecnes_imgs_business', 'BUSINESS モーダル画像', 43, array(
        'biz_trading_1' => '商社 #01 エネルギー・電力',
        'biz_trading_2' => '商社 #02 プラント産業・電機',
        'biz_trading_3' => '商社 #03 交通・施設',
        'biz_trading_4' => '商社 #04 環境機械',
        'biz_trading_5' => '商社 #05 情報・通信',
        'biz_trading_6' => '商社 #06 国際事業',
        'biz_mfg_1'     => 'モノづくり #01 自動車部品',
        'biz_mfg_2'     => 'モノづくり #02 UV システム',
    ) );

    // ===== Environment カード =====
    tecnes_register_image_section( $wp_customize, 'tecnes_imgs_env', 'ENVIRONMENT カード画像', 44, array(
        'env_1' => '福利厚生',
        'env_2' => '教育制度',
        'env_3' => 'オフィス紹介',
        'env_4' => '社員座談会 パパ・ママトーク',
        'env_5' => '社員座談会 社風トーク',
        'env_6' => '先輩たちの声',
    ) );

    // ===== ロゴ・採用ボタン =====
    tecnes_register_image_section( $wp_customize, 'tecnes_imgs_brand', 'ロゴ・採用ボタン画像', 45, array(
        'logo'             => 'サイトロゴ',
        'btn_mynavi'       => 'マイナビボタン画像',
        'btn_careertasu'   => 'キャリタスボタン画像',
    ) );
} );

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
