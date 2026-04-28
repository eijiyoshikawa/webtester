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

/**
 * Carbon Fields の Composer autoload を読み込む。
 * 検索順：
 *   1. theme/vendor/autoload.php (composer require で導入時)
 *   2. theme/lib/carbon-fields/vendor/autoload.php (手動配置時)
 *   3. theme/lib/carbon-fields/core/Carbon_Fields.php (旧式手動配置時)
 */
$anju_autoload_paths = array(
	ANJU_THEME_DIR . '/vendor/autoload.php',
	ANJU_THEME_DIR . '/lib/carbon-fields/vendor/autoload.php',
);
foreach ( $anju_autoload_paths as $anju_autoload_path ) {
	if ( file_exists( $anju_autoload_path ) ) {
		require_once $anju_autoload_path;
		break;
	}
}
unset( $anju_autoload_paths, $anju_autoload_path );

require_once ANJU_THEME_DIR . '/inc/theme-setup.php';
require_once ANJU_THEME_DIR . '/inc/enqueue.php';
require_once ANJU_THEME_DIR . '/inc/post-types.php';
require_once ANJU_THEME_DIR . '/inc/carbon-fields-setup.php';
require_once ANJU_THEME_DIR . '/inc/helpers.php';
