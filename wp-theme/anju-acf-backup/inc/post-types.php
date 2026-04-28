<?php
/**
 * Custom Post Types
 *
 * @package Anju
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * カスタム投稿タイプ「お知らせ（news）」を登録
 */
function anju_register_news_post_type() {
	register_post_type( 'news', array(
		'labels' => array(
			'name'                  => __( 'お知らせ', 'anju' ),
			'singular_name'         => __( 'お知らせ', 'anju' ),
			'menu_name'             => __( 'お知らせ', 'anju' ),
			'all_items'             => __( 'すべてのお知らせ', 'anju' ),
			'add_new'               => __( '新規追加', 'anju' ),
			'add_new_item'          => __( '新しいお知らせを追加', 'anju' ),
			'edit_item'             => __( 'お知らせを編集', 'anju' ),
			'new_item'              => __( '新しいお知らせ', 'anju' ),
			'view_item'             => __( 'お知らせを表示', 'anju' ),
			'search_items'          => __( 'お知らせを検索', 'anju' ),
			'not_found'             => __( 'お知らせが見つかりません', 'anju' ),
			'not_found_in_trash'    => __( 'ゴミ箱にお知らせはありません', 'anju' ),
			'featured_image'        => __( 'アイキャッチ画像', 'anju' ),
		),
		'public'              => true,
		'has_archive'         => 'news',
		'rewrite'             => array( 'slug' => 'news', 'with_front' => false ),
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-megaphone',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'        => true,
	) );
}
add_action( 'init', 'anju_register_news_post_type' );

/**
 * ブログ用は WordPress 標準の post を利用。
 * もし独立カスタム投稿タイプにしたい場合は以下のコメントアウトを解除：
 */
/*
function anju_register_blog_post_type() {
	register_post_type( 'blog', array(
		'labels' => array(
			'name'          => 'ブログ',
			'singular_name' => 'ブログ',
		),
		'public'      => true,
		'has_archive' => 'blog',
		'rewrite'     => array( 'slug' => 'blog', 'with_front' => false ),
		'menu_icon'   => 'dashicons-edit',
		'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'anju_register_blog_post_type' );
*/
