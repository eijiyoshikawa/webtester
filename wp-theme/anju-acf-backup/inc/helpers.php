<?php
/**
 * Helper Functions
 *
 * テンプレート内で使用するヘルパー関数群。
 *
 * @package Anju
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF Image フィールドから安全に URL を取得
 *
 * @param mixed  $image_field ACF Imageフィールドの返り値（array | int | string）
 * @param string $size 画像サイズ
 * @return string 画像URL（取得不可の場合は空文字）
 */
function anju_image_url( $image_field, $size = 'large' ) {
	if ( empty( $image_field ) ) {
		return '';
	}
	if ( is_array( $image_field ) ) {
		if ( isset( $image_field['sizes'][ $size ] ) ) {
			return $image_field['sizes'][ $size ];
		}
		return $image_field['url'] ?? '';
	}
	if ( is_numeric( $image_field ) ) {
		$src = wp_get_attachment_image_src( $image_field, $size );
		return $src[0] ?? '';
	}
	if ( is_string( $image_field ) ) {
		return $image_field;
	}
	return '';
}

/**
 * ACF Image フィールドから alt テキストを取得
 */
function anju_image_alt( $image_field, $fallback = '' ) {
	if ( is_array( $image_field ) && ! empty( $image_field['alt'] ) ) {
		return $image_field['alt'];
	}
	if ( is_numeric( $image_field ) ) {
		$alt = get_post_meta( $image_field, '_wp_attachment_image_alt', true );
		if ( $alt ) {
			return $alt;
		}
	}
	return $fallback;
}

/**
 * Options Page の ACF フィールドを取得（簡略化）
 */
function anju_option( $field_name, $default = '' ) {
	$value = get_field( $field_name, 'option' );
	return $value !== null && $value !== false && $value !== '' ? $value : $default;
}

/**
 * 現在のメニュー項目で、指定パスに該当するか判定（ナビ用 active 判定）
 */
function anju_is_current_url( $path ) {
	$current = home_url( $_SERVER['REQUEST_URI'] ?? '' );
	$check   = home_url( $path );
	return rtrim( $current, '/' ) === rtrim( $check, '/' );
}

/**
 * preg_replace で XSS 安全な inline style 用 URL 生成
 */
function anju_safe_bg_url( $url ) {
	return esc_url( $url );
}
