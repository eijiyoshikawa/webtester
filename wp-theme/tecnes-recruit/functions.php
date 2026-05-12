<?php
/**
 * TECNES Recruit theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TECNES_THEME_VERSION', '2.2.1' );

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
        'people'      => 'People.css',
        'environment' => 'Environment.css',
        'recruit'     => 'Recruit.css',
        'footer'      => 'Footer.css',
        'linecta'     => 'LineCta.css',
    );
    foreach ( $sections as $handle => $file ) {
        wp_enqueue_style( 'tecnes-' . $handle, $theme_uri . '/assets/css/' . $file, array( 'tecnes-globals' ), tecnes_asset_ver( 'assets/css/' . $file ) );
    }

    wp_enqueue_style( 'tecnes-theme-style', get_stylesheet_uri(), array(), tecnes_asset_ver( 'style.css' ) );

    wp_enqueue_script( 'splide', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js', array(), '4.1.4', true );
    wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',              array(), '11.0.0', true );

    // scroll-animator.js は意図的に enqueue しない:
    // スクロール連動の fadeIn 等はホバー以外のモーションを抑えるためオフ。
    // globals.css 側でも .js-animate を即時表示化している。
    foreach ( array(
        'tecnes-header'          => array( 'header.js', array() ),
        'tecnes-mainvisual'      => array( 'mainvisual.js', array( 'splide' ) ),
        'tecnes-business-modal'  => array( 'business-modal.js', array() ),
        'tecnes-people-swiper'   => array( 'people-swiper.js', array( 'swiper' ) ),
        'tecnes-about-counter'   => array( 'about-counter.js', array() ),
        'tecnes-recruit-tabs'    => array( 'recruit-tabs.js', array() ),
    ) as $handle => $cfg ) {
        list( $file, $deps ) = $cfg;
        wp_enqueue_script( $handle, $theme_uri . '/assets/js/' . $file, $deps, tecnes_asset_ver( 'assets/js/' . $file ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'tecnes_enqueue_assets' );

function tecnes_img( $name ) {
    return esc_url( get_template_directory_uri() . '/assets/img-v2/' . ltrim( $name, '/' ) );
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
 * Customizer のテキスト（複数行可）を取得。未設定なら $default を返す。
 *
 * get_theme_mod() は値が未保存のとき false を返すため、
 * 比較は !== にして false / null / 空文字すべてを「未設定扱い」にする。
 */
function tecnes_text( $key, $default = '' ) {
    $v = get_theme_mod( 'tecnes_text_' . $key );
    if ( $v === false || $v === null || $v === '' ) {
        return $default;
    }
    return $v;
}

/**
 * 「項目 | 内容」形式の複数行テキストを行ごとに配列化して返す。
 * 各行 → 区切り文字で split → 余白 trim。
 * 空行・全角空白行は無視。
 * 例:
 *   "募集職種 | 建設工事スタッフ\n雇用形態 | 正社員"
 *   ↓
 *   [ ['募集職種', '建設工事スタッフ'], ['雇用形態', '正社員'] ]
 */
function tecnes_parse_rows( $text, $cols = 2, $delim = '|' ) {
    $rows = array();
    if ( ! is_string( $text ) || $text === '' ) {
        return $rows;
    }
    $lines = preg_split( '/\r\n|\r|\n/', $text );
    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( $line === '' ) continue;
        $parts = array_map( 'trim', explode( $delim, $line, $cols ) );
        while ( count( $parts ) < $cols ) $parts[] = '';
        $rows[] = $parts;
    }
    return $rows;
}

/**
 * テキスト型 Customizer フィールド登録ヘルパー。
 *
 * @param WP_Customize_Manager $wp_customize
 * @param string $section_id  例: 'tecnes_text_recruit'
 * @param array  $fields      [ key => [label, default, type ('text'|'textarea')] ]
 */
function tecnes_register_text_fields( $wp_customize, $section_id, $fields ) {
    foreach ( $fields as $key => $cfg ) {
        $label   = isset( $cfg['label'] )   ? $cfg['label']   : $key;
        $default = isset( $cfg['default'] ) ? $cfg['default'] : '';
        $type    = isset( $cfg['type'] )    ? $cfg['type']    : 'text';
        $desc    = isset( $cfg['description'] ) ? $cfg['description'] : '';

        $setting_id = 'tecnes_text_' . $key;
        $wp_customize->add_setting( $setting_id, array(
            'default'           => $default,
            'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field',
        ) );
        $wp_customize->add_control( $setting_id, array(
            'label'       => $label,
            'section'     => $section_id,
            'type'        => $type,
            'description' => $desc,
        ) );
    }
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
        'ceo'           => array( '田中 義和', '代表取締役' ),
        'founded_date'  => array( '平成18年12月4日', '創立' ),
        'capital'       => array( '1,000万円', '資本金' ),
        'postal_code'   => array( '〒577-0063', '郵便番号' ),
        'address'       => array( '大阪府東大阪市川俣1-6-10', '住所' ),
        'map_url'       => array( 'https://maps.google.com/?q=%E3%80%92577-0063%20%E5%A4%A7%E9%98%AA%E5%BA%9C%E6%9D%B1%E5%A4%A7%E9%98%AA%E5%B8%82%E5%B7%9D%E4%BF%A31-6-10', 'Googleマップ URL' ),
        'tel'           => array( '', '電話番号（例: 06-0000-0000）' ),
        'station_1'     => array( '', '最寄り駅 1（例: ○○線「○○駅」より 徒歩○分）' ),
        'station_2'     => array( '', '最寄り駅 2' ),
        'station_3'     => array( '', '最寄り駅 3' ),
        'license'       => array( '国土交通大臣　許可（般-6）第27222号', '建設業許可番号' ),
        'employees'     => array( '22', '従業員数（数字のみ）' ),
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
        'line_url'        => 'LINE エントリー URL（公式LINE友達追加リンク等）',
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

    // ===== ロゴ =====
    tecnes_register_image_section( $wp_customize, 'tecnes_imgs_brand', 'ロゴ', 45, array(
        'logo' => 'サイトロゴ',
    ) );
} );

/* =============================================================
 * テキストコンテンツ Customizer 登録
 *   外観 → カスタマイズ → コンテンツ: 〇〇 セクション から編集可能。
 *   行リスト系（環境のアコーディオン、募集要項テーブル等）は
 *   「項目名 | 内容」形式の複数行テキストで一括編集する仕様。
 * ============================================================= */
add_action( 'customize_register', function ( $wp_customize ) {
    // ----- READ -----
    $wp_customize->add_section( 'tecnes_text_read', array(
        'title'    => __( 'コンテンツ: READ', 'tecnes-recruit' ),
        'priority' => 50,
    ) );
    tecnes_register_text_fields( $wp_customize, 'tecnes_text_read', array(
        'read_ttl'  => array(
            'label'   => 'メイン見出し（HTML/改行可）',
            'default' => "私たちは社会と産業の\n未来のために挑戦し続けます",
            'type'    => 'textarea',
        ),
        'read_body' => array(
            'label'   => '本文（段落間は空行、行末改行はそのまま）',
            'default' => "株式会社TECNESはただ商品を売るだけではありません。\n付加価値を加え、お客さまの課題解決に寄り添いながら\n社会インフラ整備と産業界の成長に貢献する商社です。\n\n私たちの仕事は、お客さま一人ひとりの要望に応え\n信頼を創り出すこと。\n\n勇気がいる最初の一歩も、本気で向き合う。\nどんな難題があっても、創意と工夫で乗り越えていく。\nそんな想いは、きっと誰かの未来をともす光になるはずです。\nこれが私たちの企業理念である「お役立ち」です。",
            'type'    => 'textarea',
        ),
    ) );

    // ----- BUSINESS -----
    $wp_customize->add_section( 'tecnes_text_business', array(
        'title'    => __( 'コンテンツ: BUSINESS', 'tecnes-recruit' ),
        'priority' => 51,
    ) );
    tecnes_register_text_fields( $wp_customize, 'tecnes_text_business', array(
        'business_ttl_en'   => array( 'label' => '英語見出し', 'default' => 'OUR BUSINESS' ),
        'business_ttl_ja'   => array( 'label' => '日本語見出し', 'default' => '仕事を知る' ),
        'business_lead'     => array(
            'label'   => 'リード文（<br>で改行可）',
            'default' => '電気・通信から土木・建築まで、暮らしと産業を支える幅広い建設工事に対応。<br>確かな技術とチームワークで、地域社会のインフラを支えています。',
            'type'    => 'textarea',
        ),
        'business_card1_en'   => array( 'label' => 'カード1 英語見出し', 'default' => 'ELECTRICAL' ),
        'business_card1_ja'   => array( 'label' => 'カード1 日本語見出し', 'default' => '電気・通信のしごと' ),
        'business_card1_ttl'  => array(
            'label'   => 'カード1 タイトル（<br>で改行可）',
            'default' => '情報と電力で社会を<br>つなぐ建設のプロ',
            'type'    => 'textarea',
        ),
        'business_card1_lead' => array( 'label' => 'カード1 リード', 'default' => '電気通信・電気工事・再エネ・メンテナンスの一貫対応' ),
        'business_card2_en'   => array( 'label' => 'カード2 英語見出し', 'default' => 'CIVIL' ),
        'business_card2_ja'   => array( 'label' => 'カード2 日本語見出し', 'default' => '土木・建築のしごと' ),
        'business_card2_ttl'  => array(
            'label'   => 'カード2 タイトル（<br>で改行可）',
            'default' => '街と暮らしの基盤を<br>つくる建設のプロ',
            'type'    => 'textarea',
        ),
        'business_card2_lead' => array( 'label' => 'カード2 リード', 'default' => '土木・舗装・とび土木・管工事まで、現場を支える総合力' ),
        'business_modals_elec' => array(
            'label'       => 'カード1 モーダル一覧',
            'description' => '1モーダル1行。形式: 番号|タイトル|サブタイトル|本文|キーワード1,キーワード2,キーワード3',
            'default'     => "電気・通信 #01|電気通信工事|情報通信のインフラを支える|光ファイバー、電話、LAN、無線設備などの情報通信インフラ整備を行います。オフィスや工場、公共施設のネットワーク基盤を構築し、社会のあらゆる通信を支えます。|＃光ファイバー敷設,＃LAN・通信工事,＃無線設備工事\n電気・通信 #02|電気工事|安全な電力をお届け|一般家庭から商業施設、工場まで、配電・配線、受変電設備の設置・改修を担当します。電気がなければ社会は動きません。安全と品質を最優先に施工します。|＃配電・配線工事,＃受変電設備,＃キュービクル\n電気・通信 #03|太陽光発電・再エネ工事|持続可能な未来をつくる|産業用・住宅用の太陽光発電システムの設計・施工を行います。カーボンニュートラル社会に向けて、クリーンエネルギーの導入をサポートします。|＃太陽光発電システム,＃蓄電池設置,＃再生可能エネルギー\n電気・通信 #04|メンテナンス|止まらない設備を守る|電気・通信設備の定期点検、故障時の緊急対応、改修工事まで一貫して対応します。トラブルを未然に防ぎ、お客さまの事業継続を支えます。|＃定期点検,＃緊急対応,＃設備改修",
            'type'        => 'textarea',
        ),
        'business_modals_civil' => array(
            'label'       => 'カード2 モーダル一覧',
            'description' => '1モーダル1行。形式: 番号|タイトル|サブタイトル|本文|キーワード1,キーワード2,キーワード3',
            'default'     => "土木・建築 #01|土木工事|街の基盤をつくる|道路、造成、河川、上下水道など、生活と産業を支える社会インフラの整備を行います。安全で快適な暮らしの土台を、日々の施工で築き上げています。|＃道路工事,＃造成工事,＃インフラ整備\n土木・建築 #02|舗装工事|人と車の安全を支える|アスファルト舗装、コンクリート舗装を中心に、道路や駐車場、敷地内の舗装工事を施工します。耐久性と仕上がりにこだわり、長く使える道をつくります。|＃アスファルト舗装,＃コンクリート舗装,＃駐車場・敷地舗装\n土木・建築 #03|とび・土木工事|高所と地中の現場を担う|建設現場の仮設足場、解体、基礎工事など、現場を支える基幹工事を担当します。安全教育と豊富な経験で、難しい現場にも対応します。|＃仮設足場,＃基礎工事,＃解体工事\n土木・建築 #04|管工事|生活を支える\"水\"のプロフェッショナル|給排水設備、ガス配管、空調設備の配管工事を行います。生活と産業に不可欠なライフラインを、確かな技術で支えます。|＃給排水工事,＃ガス配管,＃空調設備配管",
            'type'        => 'textarea',
        ),
    ) );

    // ----- FLOW -----
    $wp_customize->add_section( 'tecnes_text_flow', array(
        'title'    => __( 'コンテンツ: FLOW', 'tecnes-recruit' ),
        'priority' => 52,
    ) );
    tecnes_register_text_fields( $wp_customize, 'tecnes_text_flow', array(
        'flow_ttl'  => array( 'label' => '見出し', 'default' => '仕事の流れ' ),
        'flow_lead' => array(
            'label'   => 'リード文',
            'default' => '私たちはお客さまの課題解決を通して、社会インフラの発展を支えることで豊かな暮らしを実現します。',
            'type'    => 'textarea',
        ),
    ) );

    // ----- ABOUT -----
    $wp_customize->add_section( 'tecnes_text_about', array(
        'title'    => __( 'コンテンツ: ABOUT', 'tecnes-recruit' ),
        'priority' => 53,
    ) );
    tecnes_register_text_fields( $wp_customize, 'tecnes_text_about', array(
        'about_ttl_en' => array( 'label' => '英語見出し', 'default' => 'ABOUT US' ),
        'about_ttl_ja' => array( 'label' => '日本語見出し', 'default' => '株式会社TECNESについて' ),
        'about_sub'    => array( 'label' => 'サブタイトル', 'default' => '数字でわかる 株式会社TECNESの今' ),
        'about_cards'  => array(
            'label'       => '数字カード（8枚目安）',
            'description' => '1カード1行。形式: ラベル|数値|単位|補足（省略可）',
            'default'     => "創業年数|30|年|\n建設業種|6|業種|電気通信・電気・土木・舗装・とび土木・管工事\n拠点数|3|拠点|\n従業員数|22|人|\n資本金|1000|万円|\n年間休日|120|日|\n就業時間/日|8|時間|\n平均勤続年数|10|年|",
            'type'        => 'textarea',
        ),
    ) );

    // ----- PEOPLE -----
    $wp_customize->add_section( 'tecnes_text_people', array(
        'title'    => __( 'コンテンツ: PEOPLE', 'tecnes-recruit' ),
        'priority' => 54,
    ) );
    tecnes_register_text_fields( $wp_customize, 'tecnes_text_people', array(
        'people_ttl_en' => array( 'label' => '英語見出し', 'default' => 'PEOPLE' ),
        'people_ttl_ja' => array( 'label' => '日本語見出し', 'default' => '働く社員を知る' ),
        'people_lead'   => array(
            'label'   => 'リード文',
            'default' => '株式会社TECNESで新しい自分に出会い、変わらない自分の信念を持って活躍している先輩たち。どんな想いで働いているのか、エピソードを交えてご紹介します。',
            'type'    => 'textarea',
        ),
        'people_members' => array(
            'label'       => '社員一覧（7名目安）',
            'description' => '1人1行。形式: キャッチコピー|部署|役職|名前|入社年',
            'default'     => "先輩に支えられ始まった株式会社TECNESでの挑戦 次は私が後輩を支える番だ|電機システム部|営業職|M.M|2017年入社\n関わるすべての人のベクトルを合わせて 大型プロジェクトに挑む|プラント産業システム部|営業職|Y.T|2014年入社\n顧客利益だけでなく その先にある「社会に良いこと」を追求|環境・機械システム営業部|営業職|N.S|2017年入社\n主体性を持って企業と向き合い提案し、感謝される それが私の成長サイクル|新潟支店|営業職|O.T|2017年入社\n商社ならではの価値を追求し 幅広い業界へ提案 次は本社でさらなる挑戦へ|九州支店|営業職|J.A|2019年入社\n何事も全力で楽しむ 自社製品の海外展開という「未知なる挑戦」も楽しみたい|自動車部品部|営業職|W.R|2005年入社\nUVシステムの営業が切り拓く 「社会インフラ」と「持続可能な未来」への貢献|UVシステム営業部|営業職|E.M|2012年入社",
            'type'        => 'textarea',
        ),
    ) );

    // ----- ENVIRONMENT -----
    $wp_customize->add_section( 'tecnes_text_env', array(
        'title'    => __( 'コンテンツ: ENVIRONMENT', 'tecnes-recruit' ),
        'priority' => 55,
    ) );
    tecnes_register_text_fields( $wp_customize, 'tecnes_text_env', array(
        'env_ttl_en' => array( 'label' => '英語見出し', 'default' => 'ENVIRONMENT' ),
        'env_ttl_ja' => array( 'label' => '日本語見出し', 'default' => '働く環境を知る' ),
        'env_lead'   => array(
            'label'   => 'リード文',
            'default' => 'みなさんが安心して働くことのできる環境、会社とともに成長できる環境を整えております。',
            'type'    => 'textarea',
        ),
        'env_ac1_ttl'  => array( 'label' => 'アコーディオン 1 見出し', 'default' => '福利厚生' ),
        'env_ac1_rows' => array(
            'label'       => 'アコーディオン 1 内容',
            'description' => '1行 = 1項目。形式: 項目 | 内容',
            'default'     => "社会保険 | 健康保険・厚生年金・雇用保険・労災保険 完備\n休日休暇 | 完全週休2日制／祝日／年末年始／夏季休暇／有給休暇／慶弔休暇\n各種手当 | 通勤手当／資格手当／時間外手当／役職手当\n退職金制度 | あり\n健康診断 | 年1回（35歳以上は人間ドック）\n研修制度 | 入社時研修／OJT／外部研修参加支援",
            'type'        => 'textarea',
        ),
        'env_ac2_ttl'  => array( 'label' => 'アコーディオン 2 見出し', 'default' => '資格取得等褒章制度' ),
        'env_ac2_rows' => array(
            'label'       => 'アコーディオン 2 内容',
            'description' => '1行 = 1項目。形式: 項目 | 内容',
            'default'     => "資格取得支援 | 受験費用・講習費用を会社が負担\n報奨金制度 | 指定資格取得時に報奨金を支給\n対象資格例 | 電気工事士／施工管理技士／玉掛け／高所作業車運転 ほか",
            'type'        => 'textarea',
        ),
    ) );

    // ----- RECRUIT -----
    $wp_customize->add_section( 'tecnes_text_recruit', array(
        'title'    => __( 'コンテンツ: RECRUIT', 'tecnes-recruit' ),
        'priority' => 56,
    ) );
    tecnes_register_text_fields( $wp_customize, 'tecnes_text_recruit', array(
        'recruit_ttl_en' => array( 'label' => '英語見出し', 'default' => 'RECRUIT' ),
        'recruit_ttl_ja' => array( 'label' => '日本語見出し', 'default' => '募集要項' ),
        'recruit_tab1_ttl'  => array( 'label' => 'タブ 1 見出し', 'default' => '募集要項' ),
        'recruit_tab1_rows' => array(
            'label'       => 'タブ 1 内容',
            'description' => '1行 = 1項目。形式: 項目 | 内容',
            'default'     => "募集職種 | 建設工事スタッフ／施工管理／電気通信工事\n雇用形態 | 正社員\n応募資格 | 学歴・経験不問／高卒以上／普通自動車免許（AT限定可）\n勤務地 | 本社（大阪府東大阪市）または各現場\n勤務時間 | 8:00〜17:00（休憩60分／実働8時間）\n休日休暇 | 完全週休2日制／祝日／年末年始／夏季休暇／有給休暇\n給与 | 月給 23万円〜（経験・能力により優遇）\n昇給・賞与 | 昇給 年1回／賞与 年2回\n諸手当 | 通勤手当／資格手当／時間外手当／役職手当\n保険 | 健康保険・厚生年金・雇用保険・労災保険 完備\n選考フロー | 書類選考 → 面接（1〜2回）→ 内定",
            'type'        => 'textarea',
        ),
        'recruit_tab2_ttl'  => array( 'label' => 'タブ 2 見出し', 'default' => '企業・募集情報' ),
        'recruit_tab2_rows' => array(
            'label'       => 'タブ 2 内容',
            'description' => '1行 = 1項目。形式: 項目 | 内容',
            'default'     => "会社名 | 株式会社TECNES\n代表者 | 代表取締役 田中 義和\n所在地 | 〒577-0063 大阪府東大阪市川俣1-6-10\n創立 | 平成18年（2006年）12月4日\n資本金 | 1,000万円\n従業員数 | 22名\n事業内容 | 電気通信工事／電気工事／土木工事／舗装工事／とび・土木工事／管工事\n建設業許可 | 国土交通大臣許可（般-6）第27222号",
            'type'        => 'textarea',
        ),
    ) );
} );

function tecnes_line_url() {
    $u = tecnes_opt( 'line_url' );
    return $u ? esc_url( $u ) : '#';
}
