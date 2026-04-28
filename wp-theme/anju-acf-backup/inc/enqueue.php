<?php
/**
 * Asset Enqueue
 *
 * @package Anju
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * フロントエンドの CSS/JS / Google Fonts を読み込む
 */
function anju_enqueue_assets() {
	// Google Fonts
	wp_enqueue_style(
		'anju-google-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Noto+Sans+JP:wght@300;400;500;700&display=swap',
		array(),
		null
	);

	// メインスタイル
	wp_enqueue_style(
		'anju-main-style',
		ANJU_THEME_URI . '/assets/css/style.css',
		array( 'anju-google-fonts' ),
		ANJU_THEME_VERSION
	);

	// メインスクリプト
	wp_enqueue_script(
		'anju-main-script',
		ANJU_THEME_URI . '/assets/js/main.js',
		array(),
		ANJU_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'anju_enqueue_assets' );

/**
 * preconnect ヒントの追加（パフォーマンス向上）
 */
function anju_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'anju_resource_hints', 10, 2 );
