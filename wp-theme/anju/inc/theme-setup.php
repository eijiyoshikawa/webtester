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
