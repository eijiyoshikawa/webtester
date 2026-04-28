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
				->set_required( true )
				->set_help_text( 'ページ最上部の大きな画像。' ),
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
		) );

	// =========================================================================
	// グループホームページ設定
	// =========================================================================
	Container::make( 'post_meta', 'グループホームページ設定' )
		->where( 'post_template', '=', 'page-grouphome.php' )
		->add_tab( 'メイン写真', array(
			Field::make( 'image', 'grouphome_hero_image', 'メイン写真' )
				->set_value_type( 'id' )
				->set_required( true ),
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
