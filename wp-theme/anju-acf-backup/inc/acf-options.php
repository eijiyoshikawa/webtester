<?php
/**
 * ACF Options Page
 *
 * サイト共通設定（ロゴ・連絡先・SNS等）の編集画面を登録
 *
 * @package Anju
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
		'page_title' => 'サイト共通設定',
		'menu_title' => 'サイト共通設定',
		'menu_slug'  => 'site-settings',
		'capability' => 'edit_posts',
		'redirect'   => false,
		'icon_url'   => 'dashicons-admin-site-alt3',
		'position'   => 80,
	) );
}
