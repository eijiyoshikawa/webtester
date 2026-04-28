<?php
/**
 * Theme Setup
 *
 * テーマ初期化（テーマサポート登録・メニュー登録など）
 *
 * @package Anju
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * テーマ機能サポートの登録
 */
function anju_theme_setup() {
	load_theme_textdomain( 'anju', ANJU_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	add_theme_support( 'custom-logo', array(
		'height'      => 90,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	register_nav_menus( array(
		'global'       => __( 'グローバルナビゲーション', 'anju' ),
		'service_menu' => __( 'サービスサブメニュー', 'anju' ),
		'footer'       => __( 'フッターナビゲーション', 'anju' ),
	) );
}
add_action( 'after_setup_theme', 'anju_theme_setup' );

/**
 * 不要なエディタ機能を制限（編集者保護）
 */
function anju_admin_bar_setup() {
	if ( current_user_can( 'editor' ) && ! current_user_can( 'administrator' ) ) {
		// 編集者には不要なメニューを非表示
	}
}
add_action( 'admin_init', 'anju_admin_bar_setup' );

/**
 * ACF未導入時に管理画面で警告
 */
function anju_acf_missing_notice() {
	if ( ! function_exists( 'get_field' ) ) {
		echo '<div class="notice notice-error"><p><strong>Anju Theme:</strong> このテーマは「Advanced Custom Fields PRO」プラグインが必須です。インストールして有効化してください。</p></div>';
	}
}
add_action( 'admin_notices', 'anju_acf_missing_notice' );

/**
 * ACF JSON 自動同期：保存先をテーマ内 acf-json/ に
 */
function anju_acf_json_save_point( $path ) {
	return ANJU_THEME_DIR . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'anju_acf_json_save_point' );

/**
 * ACF JSON 自動同期：読み込み元
 */
function anju_acf_json_load_point( $paths ) {
	unset( $paths[0] );
	$paths[] = ANJU_THEME_DIR . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'anju_acf_json_load_point' );
