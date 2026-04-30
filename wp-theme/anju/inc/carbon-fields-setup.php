<?php
/**
 * Carbon Fields Setup
 *
 * すべてのカスタムフィールド定義を Carbon Fields で構築する。
 * ACF Pro の代替として完全無料で同等機能を提供。
 *
 * @package Anju
 * @link https://carbonfields.net/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Carbon Fields ブート
 */
function anju_carbon_fields_boot() {
	if ( class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
		\Carbon_Fields\Carbon_Fields::boot();
	}
}
add_action( 'after_setup_theme', 'anju_carbon_fields_boot', 20 );

/**
 * Carbon Fields 未導入時に管理画面で警告
 */
function anju_carbon_fields_missing_notice() {
	if ( ! class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
		echo '<div class="notice notice-error"><p><strong>Anju Theme:</strong> このテーマは「Carbon Fields」が必須です。テーマフォルダで <code>composer require htmlburger/carbon-fields</code> を実行するか、README.md の手順に従って導入してください。</p></div>';
	}
}
add_action( 'admin_notices', 'anju_carbon_fields_missing_notice' );

/**
 * フィールド定義
 */
function anju_register_carbon_fields() {
	if ( ! class_exists( '\Carbon_Fields\Container' ) ) {
		return;
	}

	// =========================================================================
	// Theme Options（サイト共通設定）
	// =========================================================================
	Container::make( 'theme_options', 'サイト共通設定' )
		->set_page_menu_position( 80 )
		->set_icon( 'dashicons-admin-site-alt3' )
		->add_tab( 'ブランド情報', array(
			Field::make( 'image', 'site_logo', '会社ロゴ' )
				->set_help_text( 'ヘッダー・フッターで使用。透過PNG推奨。' )
				->set_value_type( 'id' ),
			Field::make( 'text', 'site_company_jp', '会社名（日本語）' )
				->set_default_value( '合同会社 あんじゅ' ),
			Field::make( 'text', 'site_company_en', '会社名（英語）' )
				->set_default_value( 'Anju LLC.' ),
			Field::make( 'text', 'site_copyright', 'コピーライト表記' )
				->set_default_value( '© Anju LLC. All Rights Reserved.' ),
			Field::make( 'text', 'site_established', '設立' )
				->set_default_value( '2022年9月' ),
		) )
		->add_tab( '連絡先', array(
			Field::make( 'textarea', 'site_address', '住所' )
				->set_rows( 2 )
				->set_default_value( "〒143-0023 東京都大田区山王3-35-7\n八景坂マンション301" ),
			Field::make( 'text', 'site_tel', '電話番号' )
				->set_attribute( 'placeholder', '03-XXXX-XXXX' ),
			Field::make( 'text', 'site_email', '代表メールアドレス' )
				->set_attribute( 'type', 'email' ),
			Field::make( 'text', 'site_contact_url', 'お問い合わせフォームURL' )
				->set_default_value( '/contact/' ),
		) )
		->add_tab( 'SNS', array(
			Field::make( 'text', 'site_sns_instagram', 'Instagram URL' )
				->set_default_value( 'https://www.instagram.com/grouphome_anju/' ),
			Field::make( 'text', 'site_sns_facebook', 'Facebook URL' ),
			Field::make( 'text', 'site_sns_x', 'X (Twitter) URL' ),
			Field::make( 'text', 'site_sns_youtube', 'YouTube URL' ),
		) );

	// =========================================================================
	// トップページ設定
	// =========================================================================
	Container::make( 'post_meta', 'トップページ設定' )
		->where( 'post_template', '=', 'page-home.php' )
		->or_where( 'post_id', '=', get_option( 'page_on_front' ) )
		->add_tab( 'ヒーロー', array(
			Field::make( 'media_gallery', 'home_hero_slides', 'ヒーロースライダー画像' )
				->set_help_text( '推奨5〜8枚、1920×1080px 以上。ドラッグで並び替え可。' )
				->set_type( array( 'image' ) ),
			Field::make( 'text', 'home_hero_title', 'メインキャッチコピー' )
				->set_default_value( 'ひとつでも多くの笑顔に出会うために' ),
			Field::make( 'textarea', 'home_hero_subtitle', 'サブキャッチコピー' )
				->set_rows( 2 )
				->set_default_value( "確かな支援で今日を支え、\n明日の笑顔をつなぐ" )
				->set_help_text( '改行はそのまま反映されます。' ),
		) )
		->add_tab( 'philosophy（理念）', array(
			Field::make( 'image', 'home_philosophy_image_large', 'philosophy 大画像（上）' )
				->set_value_type( 'id' )
				->set_help_text( '理念セクションの大きな画像。横長推奨。' ),
			Field::make( 'image', 'home_philosophy_image_left', 'philosophy 小画像（下左）' )
				->set_value_type( 'id' ),
			Field::make( 'image', 'home_philosophy_image_right', 'philosophy 小画像（下右）' )
				->set_value_type( 'id' ),
			Field::make( 'textarea', 'home_philosophy_lead', 'リード文' )
				->set_rows( 2 )
				->set_default_value( "確かな支援の先にある、\n関わる「すべての人」の笑顔のために。" ),
			Field::make( 'rich_text', 'home_philosophy_body', '本文' ),
		) )
		->add_tab( '代表メッセージ', array(
			Field::make( 'image', 'home_message_photo', '代表写真' )
				->set_value_type( 'id' ),
			Field::make( 'rich_text', 'home_message_body', 'あいさつ本文' ),
			Field::make( 'text', 'home_message_signer', '代表者名' )
				->set_default_value( '吉田太樹' ),
		) )
		->add_tab( 'サービスロゴ', array(
			Field::make( 'image', 'home_logo_grouphome', 'グループホームロゴ' )
				->set_value_type( 'id' )
				->set_help_text( '推奨：透過PNG。' ),
			Field::make( 'image', 'home_logo_houkan', '訪問看護ロゴ' )
				->set_value_type( 'id' )
				->set_help_text( '推奨：透過PNG。' ),
		) );

	// =========================================================================
	// 訪問看護ページ設定
	// =========================================================================
	Container::make( 'post_meta', '訪問看護ページ設定' )
		->where( 'post_template', '=', 'page-houkan.php' )
		->add_tab( 'メイン写真', array(
			Field::make( 'image', 'houkan_hero_image', 'メイン写真' )
				->set_value_type( 'id' )
				->set_help_text( 'ページ最上部の大きな画像。未設定時はテーマ同梱画像。' ),
		) )
		->add_tab( '事業概要', array(
			Field::make( 'text', 'houkan_section_lead', 'リード文（小見出し）' )
				->set_default_value( '身近な医療の相談相手を目指して' )
				->set_help_text( '事業概要セクションの最上部に表示される小見出し。' ),
			Field::make( 'textarea', 'houkan_section_desc', '事業概要 説明文' )
				->set_rows( 4 )
				->set_default_value( '合同会社あんじゅでは、大田区山王を拠点に訪問看護ステーションを運営しています。医師や薬剤師など関係者の方々、また利用者さまを日々サポートされているご家族や支援者の方々とも密に連携を取り、利用者さまが安心して暮らせる環境づくりに取り組んでいます。' )
				->set_help_text( '改行はそのまま反映されます。本文エディタが入力されている場合はそちらが優先されます。' ),
		) )
		->add_tab( '事業内容（訪問看護）', array(
			Field::make( 'text', 'houkan_subsection_heading', '見出し' )
				->set_default_value( '訪問看護ステーションあんじゅ' ),
			Field::make( 'text', 'houkan_subsection_heading_sub', '見出し補足' )
				->set_default_value( '（大田区山王）' ),
			Field::make( 'text', 'houkan_lead_title', 'リードタイトル' )
				->set_default_value( '身近な医療の相談相手。そばで支える人にも寄り添う訪問看護を。' ),
			Field::make( 'rich_text', 'houkan_lead_body', 'リード本文' )
				->set_default_value( '<p>心と体の相談に乗り、医師と連携して安心した地域生活をサポートします。</p>' ),
		) )
		->add_tab( '＜訪看の強み＞', array(
			Field::make( 'text', 'houkan_strength_heading', '強み 見出し' )
				->set_default_value( '＜あんじゅ訪看の強み＞' ),
			Field::make( 'textarea', 'houkan_strength_lead', '強み リード文' )
				->set_rows( 3 )
				->set_default_value( '障害者グループホームを運営する訪問看護ステーションだからこその強みがあります。看護師全員が障害者グループホームでの世話人業務経験者です。' ),
			Field::make( 'complex', 'houkan_strength_items', '強み一覧' )
				->set_layout( 'tabbed-vertical' )
				->add_fields( array(
					Field::make( 'textarea', 'text', '本文' )->set_rows( 2 )->set_required( true ),
				) )
				->set_header_template( '<%- text %>' )
				->set_help_text( '「行を追加」で項目を追加できます。' ),
		) )
		->add_tab( 'サイドバー（事業内容）', array(
			Field::make( 'complex', 'houkan_sidebar_blocks', 'サイドバー ブロック' )
				->set_layout( 'tabbed-vertical' )
				->add_fields( array(
					Field::make( 'text', 'heading', '見出し' )->set_required( true ),
					Field::make( 'textarea', 'body', '本文' )
						->set_rows( 3 )
						->set_help_text( '改行で複数行表示できます。' ),
				) )
				->set_header_template( '<%- heading %>' )
				->set_help_text( '例：在籍スタッフ数 / 営業日及び営業時間 / 訪問看護提供地域 など' ),
		) )
		->add_tab( '料金表・サービスPDF', array(
			Field::make( 'file', 'houkan_price_pdf', '料金表PDF' )
				->set_type( array( 'application/pdf' ) )
				->set_value_type( 'id' ),
			Field::make( 'text', 'houkan_price_title', '料金表タイトル' )
				->set_default_value( '訪問看護 料金表' ),
			Field::make( 'textarea', 'houkan_price_desc', '料金表説明' )
				->set_rows( 2 )
				->set_default_value( '基本料金・加算項目をまとめた料金一覧です。' ),
			Field::make( 'file', 'houkan_service_pdf', 'サービス詳細PDF' )
				->set_type( array( 'application/pdf' ) )
				->set_value_type( 'id' ),
			Field::make( 'text', 'houkan_service_title', 'サービス詳細タイトル' )
				->set_default_value( 'サービス詳細' ),
			Field::make( 'textarea', 'houkan_service_desc', 'サービス詳細説明' )
				->set_rows( 2 )
				->set_default_value( '提供サービスの内容・対象・利用方法をご案内します。' ),
		) )
		->add_tab( 'ギャラリー', array(
			Field::make( 'media_gallery', 'houkan_gallery', 'ギャラリー画像' )
				->set_help_text( '推奨6〜12枚。スライダーで自動表示されます。' )
				->set_type( array( 'image' ) ),
		) )
		->add_tab( 'our policy', array(
			Field::make( 'textarea', 'houkan_policy_text', 'our policy 本文' )
				->set_rows( 5 )
				->set_default_value( '私たちは、利用者さまが笑顔で暮らし、スタッフが健康的に働けることが、より良い医療福祉サービスを生み出すと信じています。心と体の健康を支え、地域での暮らしに寄り添う訪問看護を提供していきたいと考えています。' ),
		) );

	// =========================================================================
	// グループホームページ設定
	// =========================================================================
	Container::make( 'post_meta', 'グループホームページ設定' )
		->where( 'post_template', '=', 'page-grouphome.php' )
		->add_tab( 'メイン写真', array(
			Field::make( 'image', 'grouphome_hero_image', 'メイン写真' )
				->set_value_type( 'id' )
				->set_help_text( 'ページ最上部の大きな画像。未設定時はテーマ同梱画像。' ),
		) )
		->add_tab( '事業概要', array(
			Field::make( 'text', 'grouphome_section_lead', 'リード文（小見出し）' )
				->set_default_value( '安心して穏やかに過ごせるホームを目指して' ),
			Field::make( 'textarea', 'grouphome_section_desc', '事業概要 説明文' )
				->set_rows( 4 )
				->set_default_value( '合同会社あんじゅでは、大田区内で知的障害や精神障害をお持ちの方のグループホーム（共同生活援助）を運営しています。地域や支援者の方々、医療職の方とも手を取り合い、利用者さまが安心して暮らせる環境づくりに取り組んでいます。' )
				->set_help_text( '改行はそのまま反映されます。本文エディタが入力されている場合はそちらが優先されます。' ),
		) )
		->add_tab( '事業内容（グループホーム）', array(
			Field::make( 'text', 'grouphome_subsection_heading', '見出し' )
				->set_default_value( 'グループホームあんじゅ' ),
			Field::make( 'text', 'grouphome_subsection_heading_sub', '見出し補足' )
				->set_default_value( '（大田区・4事業所）' ),
			Field::make( 'textarea', 'grouphome_subsection_intro', '導入文' )
				->set_rows( 2 )
				->set_default_value( '大田区内で知的障害や精神障害をお持ちの方のグループホーム（共同生活援助）を運営しています。' ),
			Field::make( 'text', 'grouphome_lead_title', 'リードタイトル' )
				->set_default_value( '安心して穏やかに過ごせる、もう一つの居場所を目指して。' ),
			Field::make( 'rich_text', 'grouphome_lead_body', 'リード本文' )
				->set_default_value( '<p>利用者さまが安心して笑顔で暮らし、同時にスタッフも笑顔でいられることが、よりよい支援を生み、それが継続できるものと考えています。</p><p>共同生活を通じて、ひとりじゃない、一緒だったら何かできるかも…を探せる環境を用意していきたいと思います。</p>' ),
		) )
		->add_tab( 'ホーム一覧', array(
			Field::make( 'complex', 'grouphome_homes', 'ホーム一覧' )
				->set_help_text( '各拠点（あんじゅ大森南など）の情報を管理します。「行を追加」で拠点追加可能。' )
				->set_layout( 'tabbed-vertical' )
				->add_fields( array(
					Field::make( 'text', 'name', '拠点名' )
						->set_required( true )
						->set_attribute( 'placeholder', '例：あんじゅ大森南' ),
					Field::make( 'text', 'address', '住所' )
						->set_required( true )
						->set_attribute( 'placeholder', '例：東京都大田区大森南3-29-10' ),
					Field::make( 'image', 'image_left', '左側写真' )
						->set_value_type( 'id' )
						->set_required( true ),
					Field::make( 'image', 'image_right', '右側写真' )
						->set_value_type( 'id' )
						->set_required( true ),
				) )
				->set_header_template( '<%- name %>' ),
		) )
		->add_tab( 'ギャラリー', array(
			Field::make( 'media_gallery', 'grouphome_gallery', 'ギャラリー画像' )
				->set_help_text( '推奨6〜12枚。スライダーで自動表示されます。' )
				->set_type( array( 'image' ) ),
		) )
		->add_tab( 'our policy', array(
			Field::make( 'textarea', 'grouphome_policy_text', 'our policy 本文' )
				->set_rows( 5 )
				->set_default_value( '私たちは、利用者さまが笑顔で暮らし、スタッフが健康的に働けることが、より良い福祉サービスを生み出すと信じています。グループホームという共同生活を通じて、ひとりじゃない、一緒だったら何かできるかも…を探せる環境を用意していきたいと考えています。' ),
		) );

	// =========================================================================
	// お問い合わせページ設定
	// =========================================================================
	Container::make( 'post_meta', 'お問い合わせページ設定' )
		->where( 'post_template', '=', 'page-contact.php' )
		->add_fields( array(
			Field::make( 'text', 'contact_cf7_shortcode', 'Contact Form 7 ショートコード' )
				->set_help_text( '例: [contact-form-7 id="123" title="お問い合わせ"]' ),
		) );
}
add_action( 'carbon_fields_register_fields', 'anju_register_carbon_fields' );
