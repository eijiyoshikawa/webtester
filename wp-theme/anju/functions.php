<?php
/**
 * Anju Theme Functions
 *
 * このファイルはテーマのエントリポイント。
 * 機能ごとに inc/ 配下のファイルへ分割しています。
 *
 * @package Anju
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ANJU_THEME_VERSION', '1.0.0' );
define( 'ANJU_THEME_DIR', get_template_directory() );
define( 'ANJU_THEME_URI', get_template_directory_uri() );

require_once ANJU_THEME_DIR . '/inc/theme-setup.php';
require_once ANJU_THEME_DIR . '/inc/enqueue.php';
require_once ANJU_THEME_DIR . '/inc/post-types.php';
require_once ANJU_THEME_DIR . '/inc/acf-options.php';
require_once ANJU_THEME_DIR . '/inc/helpers.php';
