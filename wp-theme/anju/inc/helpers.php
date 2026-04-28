<?php
/**
 * Helper Functions
 *
 * テンプレート内で使用するヘルパー関数群。Carbon Fields のデータ形式を
 * 抽象化して、テンプレート側でシンプルに値を取り出せるようにする。
 *
 * @package Anju
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 添付ファイルID から URL を取得（画像・PDF共通）
 *
 * @param int|string $attachment_id 添付ファイルID（Carbon Fields の image / file フィールドの返り値）
 * @param string     $size 画像サイズ（'full' | 'large' | 'medium_large' | 'medium' | 'thumbnail'）
 * @return string 取得不可なら空文字
 */
function anju_image_url( $attachment_id, $size = 'large' ) {
	if ( empty( $attachment_id ) ) {
		return '';
	}

	// 配列形式（古いコードとの後方互換）
	if ( is_array( $attachment_id ) ) {
		if ( ! empty( $attachment_id['sizes'][ $size ] ) ) {
			return $attachment_id['sizes'][ $size ];
		}
		return $attachment_id['url'] ?? '';
	}

	// 数値ID（Carbon Fields の image / file フィールドの標準）
	if ( is_numeric( $attachment_id ) ) {
		$src = wp_get_attachment_image_src( (int) $attachment_id, $size );
		if ( $src && ! empty( $src[0] ) ) {
			return $src[0];
		}
		// 画像以外（PDF等）の場合
		$url = wp_get_attachment_url( (int) $attachment_id );
		return $url ?: '';
	}

	// URL 文字列がそのまま入っている場合
	if ( is_string( $attachment_id ) ) {
		return $attachment_id;
	}

	return '';
}

/**
 * 添付ファイルID から alt テキストを取得
 */
function anju_image_alt( $attachment_id, $fallback = '' ) {
	if ( is_numeric( $attachment_id ) ) {
		$alt = get_post_meta( (int) $attachment_id, '_wp_attachment_image_alt', true );
		if ( $alt ) {
			return $alt;
		}
	}
	if ( is_array( $attachment_id ) && ! empty( $attachment_id['alt'] ) ) {
		return $attachment_id['alt'];
	}
	return $fallback;
}

/**
 * Theme Options の値を取得（Carbon Fields）
 *
 * @param string $key       フィールドキー
 * @param mixed  $default   未設定時のフォールバック値
 * @return mixed
 */
function anju_option( $key, $default = '' ) {
	if ( ! function_exists( 'carbon_get_theme_option' ) ) {
		return $default;
	}
	$value = carbon_get_theme_option( $key );
	if ( $value === null || $value === false || $value === '' || $value === array() ) {
		return $default;
	}
	return $value;
}

/**
 * 現在の投稿のメタ値を取得（Carbon Fields）
 *
 * @param string   $key      フィールドキー
 * @param int|null $post_id  投稿ID（省略時は現在の投稿）
 * @param mixed    $default  未設定時のフォールバック値
 * @param string   $type     'simple' | 'complex' | 'media_gallery'
 * @return mixed
 */
function anju_meta( $key, $post_id = null, $default = '', $type = 'simple' ) {
	if ( ! function_exists( 'carbon_get_post_meta' ) ) {
		return $default;
	}
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}

	if ( 'complex' === $type ) {
		$value = carbon_get_post_meta( $post_id, $key, 'complex' );
	} else {
		$value = carbon_get_post_meta( $post_id, $key );
	}

	if ( $value === null || $value === false || $value === '' || $value === array() ) {
		return $default;
	}
	return $value;
}

/**
 * 現在ページが指定パスかどうかを判定
 */
function anju_is_current_url( $path ) {
	$current = home_url( $_SERVER['REQUEST_URI'] ?? '' );
	$check   = home_url( $path );
	return rtrim( $current, '/' ) === rtrim( $check, '/' );
}
