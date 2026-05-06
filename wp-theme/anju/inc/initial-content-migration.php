<?php
/**
 * Initial Content Migration (v1)
 *
 * 各固定ページのカスタムフィールド／本文に既定値を一度だけ自動投入する。
 * - 空のフィールドだけを埋める（既存値は絶対に上書きしない）
 * - 管理者権限ユーザーが管理画面を開いたときだけ実行
 * - wp_options に `anju_initial_content_imported_v1` を立てて二重実行を防止
 * - 投入前後の値を `anju_initial_content_import_log_v1` に保存（ロールバック用）
 *
 * @package Anju
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ANJU_MIGRATION_FLAG_KEY = 'anju_initial_content_imported_v1';
const ANJU_MIGRATION_LOG_KEY  = 'anju_initial_content_import_log_v1';

/**
 * admin_init で1回だけ自動実行
 */
function anju_run_initial_content_migration() {
	if ( wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_option( ANJU_MIGRATION_FLAG_KEY ) ) {
		return;
	}
	if ( ! function_exists( 'carbon_get_post_meta' ) ) {
		return;
	}

	$log = array(
		'started_at' => current_time( 'mysql' ),
		'inserted'   => array(),
		'skipped'    => array(),
	);

	anju_migrate_theme_options( $log );
	anju_migrate_front_page( $log );
	anju_migrate_houkan( $log );
	anju_migrate_grouphome( $log );
	anju_migrate_contact( $log );
	anju_migrate_privacy_policy( $log );
	anju_migrate_manual( $log );

	$log['finished_at'] = current_time( 'mysql' );

	update_option( ANJU_MIGRATION_LOG_KEY, $log, false );
	update_option( ANJU_MIGRATION_FLAG_KEY, 1, false );

	set_transient( 'anju_migration_just_ran', $log, 5 * MINUTE_IN_SECONDS );
}
add_action( 'admin_init', 'anju_run_initial_content_migration', 99 );

/**
 * テンプレートファイルから固定ページIDを取得
 */
function anju_find_page_by_template( $template_filename ) {
	$pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => array( 'publish', 'draft', 'private' ),
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => $template_filename,
		'fields'         => 'ids',
	) );
	return $pages ? (int) $pages[0] : 0;
}

/**
 * Carbon Fields の simple フィールドを空の場合のみ更新
 *
 * @return string 'inserted' | 'skipped' | 'no-page'
 */
function anju_migrate_field_if_empty( $post_id, $key, $value ) {
	if ( ! $post_id ) {
		return 'no-page';
	}
	$current = carbon_get_post_meta( $post_id, $key );
	if ( $current === null || $current === '' || $current === false || $current === array() ) {
		carbon_set_post_meta( $post_id, $key, $value );
		return 'inserted';
	}
	return 'skipped';
}

/**
 * Theme Option を空の場合のみ更新
 */
function anju_migrate_option_if_empty( $key, $value ) {
	$current = carbon_get_theme_option( $key );
	if ( $current === null || $current === '' || $current === false || $current === array() ) {
		carbon_set_theme_option( $key, $value );
		return 'inserted';
	}
	return 'skipped';
}

/**
 * post_content を空の場合のみ更新
 */
function anju_migrate_post_content_if_empty( $post_id, $content ) {
	if ( ! $post_id ) {
		return 'no-page';
	}
	$post = get_post( $post_id );
	if ( ! $post ) {
		return 'no-page';
	}
	if ( trim( wp_strip_all_tags( $post->post_content ) ) === '' ) {
		wp_update_post( array(
			'ID'           => $post_id,
			'post_content' => $content,
		) );
		return 'inserted';
	}
	return 'skipped';
}

/**
 * ログ記録ヘルパー
 */
function anju_migration_log( &$log, $page, $field, $result, $value_preview = '' ) {
	$entry = array(
		'page'    => $page,
		'field'   => $field,
		'preview' => is_string( $value_preview ) ? mb_substr( $value_preview, 0, 60 ) : '(non-string)',
	);
	if ( $result === 'inserted' ) {
		$log['inserted'][] = $entry;
	} else {
		$log['skipped'][] = $entry;
	}
}

// =========================================================================
// 各ページの migration 定義
// =========================================================================

function anju_migrate_theme_options( &$log ) {
	$defaults = array(
		'site_company_jp'    => '合同会社 あんじゅ',
		'site_company_en'    => 'Anju LLC.',
		'site_copyright'     => '© Anju LLC. All Rights Reserved.',
		'site_established'   => '2022年9月',
		'site_address'       => "〒143-0023 東京都大田区山王3-35-7\n八景坂マンション301",
		'site_contact_url'   => '/contact/',
		'site_sns_instagram' => 'https://www.instagram.com/grouphome_anju/',
	);
	foreach ( $defaults as $key => $value ) {
		$result = anju_migrate_option_if_empty( $key, $value );
		anju_migration_log( $log, 'theme_options', $key, $result, $value );
	}
}

function anju_migrate_front_page( &$log ) {
	$post_id = (int) get_option( 'page_on_front' );
	if ( ! $post_id ) {
		return;
	}

	$message_body  = "合同会社あんじゅは大田区内で2023年からは障害をお持ちの方向けのグループホームを、2025年からはより良い医療福祉の形を目指し訪問看護ステーションを運営しています。\n\n";
	$message_body .= "理念である「関わるすべての人の笑顔の実現」に向けて、柔軟な思考を持ち、業界の垣根を越えての協働を推進しています。\n\n";
	$message_body .= '医療福祉や地域社会の明日がしなやかで生き生きとしたものになることを信じて、仲間と共に挑戦を続けてまいります。';

	$defaults = array(
		'home_hero_title'      => 'ひとつでも多くの笑顔に出会うために',
		'home_hero_subtitle'   => "確かな支援で今日を支え、\n明日の笑顔をつなぐ",
		'home_philosophy_lead' => "確かな支援の先にある、\n関わる「すべての人」の笑顔のために。",
		'home_message_body'    => $message_body,
		'home_message_signer'  => '吉田太樹',
	);
	foreach ( $defaults as $key => $value ) {
		$result = anju_migrate_field_if_empty( $post_id, $key, $value );
		anju_migration_log( $log, 'front_page', $key, $result, $value );
	}
}

function anju_migrate_houkan( &$log ) {
	$post_id = anju_find_page_by_template( 'page-houkan.php' );
	if ( ! $post_id ) {
		return;
	}

	$defaults = array(
		'houkan_section_lead'           => '身近な医療の相談相手を目指して',
		'houkan_section_desc'           => '合同会社あんじゅでは、大田区山王を拠点に訪問看護ステーションを運営しています。医師や薬剤師など関係者の方々、また利用者さまを日々サポートされているご家族や支援者の方々とも密に連携を取り、利用者さまが安心して暮らせる環境づくりに取り組んでいます。',
		'houkan_subsection_heading'     => '訪問看護ステーションあんじゅ',
		'houkan_subsection_heading_sub' => '（大田区山王）',
		'houkan_lead_title'             => '身近な医療の相談相手。そばで支える人にも寄り添う訪問看護を。',
		'houkan_lead_body'              => '<p>心と体の相談に乗り、医師と連携して安心した地域生活をサポートします。</p>',
		'houkan_strength_heading'       => '＜あんじゅ訪看の強み＞',
		'houkan_strength_lead'          => '障害者グループホームを運営する訪問看護ステーションだからこその強みがあります。看護師全員が障害者グループホームでの世話人業務経験者です。',
		'houkan_price_title'            => '訪問看護 料金表',
		'houkan_price_desc'             => '基本料金・加算項目をまとめた料金一覧です。',
		'houkan_service_title'          => 'サービス詳細',
		'houkan_service_desc'           => '提供サービスの内容・対象・利用方法をご案内します。',
		'houkan_policy_text'            => '私たちは、利用者さまが笑顔で暮らし、スタッフが健康的に働けることが、より良い医療福祉サービスを生み出すと信じています。心と体の健康を支え、地域での暮らしに寄り添う訪問看護を提供していきたいと考えています。',
	);
	foreach ( $defaults as $key => $value ) {
		$result = anju_migrate_field_if_empty( $post_id, $key, $value );
		anju_migration_log( $log, 'houkan', $key, $result, $value );
	}

	$strength_items = array(
		array( 'text' => 'おひとりおひとりの障害特性や個別支援計画を理解し、自立支援や社会参加支援のサポートをいたします。' ),
		array( 'text' => '利用者様の日中活動を制限しないよう、柔軟に訪問時間を調整いたします。' ),
		array( 'text' => '支援者様にもしっかり寄り添いながら、最善の看護支援を目指します。' ),
	);
	$result = anju_migrate_field_if_empty( $post_id, 'houkan_strength_items', $strength_items );
	anju_migration_log( $log, 'houkan', 'houkan_strength_items', $result, '(complex: 3 items)' );
}

function anju_migrate_grouphome( &$log ) {
	$post_id = anju_find_page_by_template( 'page-grouphome.php' );
	if ( ! $post_id ) {
		return;
	}

	$defaults = array(
		'grouphome_section_lead'           => '安心して穏やかに過ごせるホームを目指して',
		'grouphome_section_desc'           => '合同会社あんじゅでは、大田区内で知的障害や精神障害をお持ちの方のグループホーム（共同生活援助）を運営しています。地域や支援者の方々、医療職の方とも手を取り合い、利用者さまが安心して暮らせる環境づくりに取り組んでいます。',
		'grouphome_subsection_heading'     => 'グループホームあんじゅ',
		'grouphome_subsection_heading_sub' => '（大田区・4事業所）',
		'grouphome_subsection_intro'       => '大田区内で知的障害や精神障害をお持ちの方のグループホーム（共同生活援助）を運営しています。',
		'grouphome_lead_title'             => '安心して穏やかに過ごせる、もう一つの居場所を目指して。',
		'grouphome_lead_body'              => '<p>利用者さまが安心して笑顔で暮らし、同時にスタッフも笑顔でいられることが、よりよい支援を生み、それが継続できるものと考えています。</p><p>共同生活を通じて、ひとりじゃない、一緒だったら何かできるかも…を探せる環境を用意していきたいと思います。</p>',
		'grouphome_policy_text'            => '私たちは、利用者さまが笑顔で暮らし、スタッフが健康的に働けることが、より良い福祉サービスを生み出すと信じています。グループホームという共同生活を通じて、ひとりじゃない、一緒だったら何かできるかも…を探せる環境を用意していきたいと考えています。',
	);
	foreach ( $defaults as $key => $value ) {
		$result = anju_migrate_field_if_empty( $post_id, $key, $value );
		anju_migration_log( $log, 'grouphome', $key, $result, $value );
	}
}

function anju_migrate_contact( &$log ) {
	$post_id = anju_find_page_by_template( 'page-contact.php' );
	if ( ! $post_id ) {
		return;
	}
	$result = anju_migrate_field_if_empty( $post_id, 'contact_cf7_shortcode', '[contact-form-7 id="772ed89" title="contactformsystem"]' );
	anju_migration_log( $log, 'contact', 'contact_cf7_shortcode', $result, '[contact-form-7 ...]' );
}

function anju_migrate_privacy_policy( &$log ) {
	$post_id = anju_find_page_by_template( 'page-privacy-policy.php' );
	if ( ! $post_id ) {
		return;
	}
	$content = anju_build_privacy_policy_content();
	$result  = anju_migrate_post_content_if_empty( $post_id, $content );
	anju_migration_log( $log, 'privacy_policy', 'post_content', $result, '(privacy policy full text)' );
}

function anju_migrate_manual( &$log ) {
	$post_id = anju_find_page_by_template( 'page-manual.php' );
	if ( ! $post_id ) {
		return;
	}
	$md_path = ANJU_THEME_DIR . '/../../docs/site-operations-manual.md';
	if ( ! file_exists( $md_path ) ) {
		// docs ディレクトリがテーマと同階層にない場合のフォールバック
		$alt = realpath( ANJU_THEME_DIR . '/../../../docs/site-operations-manual.md' );
		if ( $alt && file_exists( $alt ) ) {
			$md_path = $alt;
		}
	}
	if ( ! file_exists( $md_path ) ) {
		anju_migration_log( $log, 'manual', 'post_content', 'skipped', '(md file not found)' );
		return;
	}
	$md      = file_get_contents( $md_path );
	$html    = anju_simple_md_to_html( $md );
	$content = "<!-- wp:html -->\n" . $html . "\n<!-- /wp:html -->";
	$result  = anju_migrate_post_content_if_empty( $post_id, $content );
	anju_migration_log( $log, 'manual', 'post_content', $result, '(manual: ' . strlen( $html ) . ' bytes)' );
}

// =========================================================================
// プライバシーポリシー本文ビルダー
// =========================================================================

function anju_build_privacy_policy_content() {
	$company     = function_exists( 'carbon_get_theme_option' ) ? carbon_get_theme_option( 'site_company_jp' ) : '';
	$company     = $company ?: '合同会社あんじゅ';
	$contact_url = function_exists( 'carbon_get_theme_option' ) ? carbon_get_theme_option( 'site_contact_url' ) : '';
	$contact_url = $contact_url ?: home_url( '/contact/' );

	$blocks = array();

	$blocks[] = '<!-- wp:paragraph --><p>' . esc_html( $company ) . '（以下「当社」といいます。）は、利用者さまの個人情報の重要性を認識し、その保護の徹底を図るため、以下のとおり個人情報保護方針を定め、これを遵守いたします。</p><!-- /wp:paragraph -->';

	$sections = array(
		array(
			'h' => '1. 個人情報の取得について',
			'p' => array( '当社は、サービスの提供および当社業務の遂行に必要な範囲内で、適法かつ公正な手段により個人情報を取得します。' ),
		),
		array(
			'h' => '2. 個人情報の利用目的',
			'p' => array( '当社は、取得した個人情報を以下の目的のために利用します。' ),
			'l' => array(
				'グループホーム事業および訪問看護事業に係るサービス提供のため',
				'お問い合わせへのご回答のため',
				'関係機関との連携・連絡のため',
				'当社サービスに関するご案内のため',
				'その他、上記の目的に付随する業務の遂行のため',
			),
		),
		array(
			'h' => '3. 個人情報の第三者提供',
			'p' => array( '当社は、法令に定める場合を除き、ご本人の同意を得ることなく、取得した個人情報を第三者に提供することはありません。' ),
		),
		array(
			'h' => '4. 個人情報の安全管理',
			'p' => array( '当社は、取得した個人情報の漏えい、滅失またはき損の防止その他個人情報の安全管理のために、必要かつ適切な措置を講じます。' ),
		),
		array(
			'h' => '5. 個人情報の開示・訂正・削除等',
			'p' => array( 'ご本人から個人情報の開示・訂正・削除等のご要望があった場合には、本人確認を行ったうえで、合理的な範囲で速やかに対応いたします。' ),
		),
		array(
			'h' => '6. 法令・規範の遵守と見直し',
			'p' => array( '当社は、保有する個人情報に関して適用される日本の法令、その他規範を遵守するとともに、本ポリシーの内容を適宜見直し、その改善に努めます。' ),
		),
	);

	foreach ( $sections as $section ) {
		$blocks[] = '<!-- wp:heading {"level":2} --><h2>' . esc_html( $section['h'] ) . '</h2><!-- /wp:heading -->';
		foreach ( $section['p'] as $p ) {
			$blocks[] = '<!-- wp:paragraph --><p>' . esc_html( $p ) . '</p><!-- /wp:paragraph -->';
		}
		if ( ! empty( $section['l'] ) ) {
			$items = '';
			foreach ( $section['l'] as $li ) {
				$items .= '<li>' . esc_html( $li ) . '</li>';
			}
			$blocks[] = '<!-- wp:list --><ul>' . $items . '</ul><!-- /wp:list -->';
		}
	}

	$blocks[] = '<!-- wp:heading {"level":2} --><h2>7. お問い合わせ窓口</h2><!-- /wp:heading -->';
	$blocks[] = '<!-- wp:paragraph --><p>個人情報の取扱いに関するお問い合わせは、以下の窓口までご連絡ください。</p><!-- /wp:paragraph -->';
	$blocks[] = '<!-- wp:paragraph --><p>' . esc_html( $company ) . '<br>お問い合わせフォーム: <a href="' . esc_url( $contact_url ) . '">こちら</a></p><!-- /wp:paragraph -->';
	$blocks[] = '<!-- wp:paragraph --><p>制定日：2024年1月1日</p><!-- /wp:paragraph -->';

	return implode( "\n\n", $blocks );
}

// =========================================================================
// 簡易 Markdown → HTML 変換
// =========================================================================

function anju_simple_md_to_html( $md ) {
	$lines  = preg_split( '/\r\n|\r|\n/', $md );
	$out    = array();
	$state  = array(
		'list_type'    => null,    // 'ul' | 'ol' | null
		'in_blockquote' => false,
		'in_table'     => false,
		'table_rows'   => array(),
	);

	$close_block = function () use ( &$state, &$out ) {
		if ( $state['list_type'] ) {
			$out[]              = '</' . $state['list_type'] . '>';
			$state['list_type'] = null;
		}
		if ( $state['in_blockquote'] ) {
			$out[]                = '</blockquote>';
			$state['in_blockquote'] = false;
		}
		if ( $state['in_table'] ) {
			$out[] = anju_md_render_table( $state['table_rows'] );
			$state['in_table']   = false;
			$state['table_rows'] = array();
		}
	};

	foreach ( $lines as $raw ) {
		$line = rtrim( $raw );

		// 水平線
		if ( preg_match( '/^---+\s*$/', $line ) ) {
			$close_block();
			$out[] = '<hr>';
			continue;
		}

		// 空行
		if ( $line === '' ) {
			$close_block();
			continue;
		}

		// 見出し
		if ( preg_match( '/^(#{1,6})\s+(.*)$/u', $line, $m ) ) {
			$close_block();
			$level = strlen( $m[1] );
			$out[] = '<h' . $level . '>' . anju_md_inline( $m[2] ) . '</h' . $level . '>';
			continue;
		}

		// 引用
		if ( preg_match( '/^>\s?(.*)$/u', $line, $m ) ) {
			if ( ! $state['in_blockquote'] ) {
				$close_block();
				$out[]                 = '<blockquote>';
				$state['in_blockquote'] = true;
			}
			$out[] = '<p>' . anju_md_inline( $m[1] ) . '</p>';
			continue;
		}

		// テーブル
		if ( preg_match( '/^\|(.+)\|\s*$/u', $line, $m ) ) {
			if ( ! $state['in_table'] ) {
				$close_block();
				$state['in_table']   = true;
				$state['table_rows'] = array();
			}
			$state['table_rows'][] = $m[1];
			continue;
		}

		// 順序リスト
		if ( preg_match( '/^\s*\d+\.\s+(.*)$/u', $line, $m ) ) {
			if ( $state['list_type'] !== 'ol' ) {
				$close_block();
				$out[]              = '<ol>';
				$state['list_type'] = 'ol';
			}
			$out[] = '<li>' . anju_md_inline( $m[1] ) . '</li>';
			continue;
		}

		// 箇条書き
		if ( preg_match( '/^\s*[-*]\s+(.*)$/u', $line, $m ) ) {
			if ( $state['list_type'] !== 'ul' ) {
				$close_block();
				$out[]              = '<ul>';
				$state['list_type'] = 'ul';
			}
			$out[] = '<li>' . anju_md_inline( $m[1] ) . '</li>';
			continue;
		}

		// 段落
		$close_block();
		$out[] = '<p>' . anju_md_inline( $line ) . '</p>';
	}

	$close_block();

	return implode( "\n", $out );
}

function anju_md_inline( $s ) {
	// エスケープしてから記法を変換（属性混入を防ぐ）
	$s = htmlspecialchars( $s, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	// リンク [text](url)
	$s = preg_replace_callback( '/\[([^\]]+)\]\(([^)]+)\)/u', function ( $m ) {
		$href = htmlspecialchars( html_entity_decode( $m[2], ENT_QUOTES, 'UTF-8' ), ENT_QUOTES );
		return '<a href="' . $href . '">' . $m[1] . '</a>';
	}, $s );
	// 太字 **text**
	$s = preg_replace( '/\*\*([^*]+)\*\*/u', '<strong>$1</strong>', $s );
	// インラインコード `code`
	$s = preg_replace( '/`([^`]+)`/u', '<code>$1</code>', $s );
	return $s;
}

function anju_md_render_table( $rows ) {
	if ( count( $rows ) < 2 ) {
		return '';
	}
	$parse_row = function ( $row ) {
		$cells = array_map( 'trim', explode( '|', $row ) );
		return $cells;
	};
	$header = $parse_row( array_shift( $rows ) );
	// 区切り行（|---|---|）をスキップ
	if ( ! empty( $rows ) && preg_match( '/^[\s\-:|]+$/', $rows[0] ) ) {
		array_shift( $rows );
	}
	$html = '<table><thead><tr>';
	foreach ( $header as $h ) {
		$html .= '<th>' . anju_md_inline( $h ) . '</th>';
	}
	$html .= '</tr></thead><tbody>';
	foreach ( $rows as $row ) {
		$cells = $parse_row( $row );
		$html .= '<tr>';
		foreach ( $cells as $c ) {
			$html .= '<td>' . anju_md_inline( $c ) . '</td>';
		}
		$html .= '</tr>';
	}
	$html .= '</tbody></table>';
	return $html;
}

// =========================================================================
// 完了通知
// =========================================================================

function anju_migration_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$log = get_transient( 'anju_migration_just_ran' );
	if ( ! $log ) {
		return;
	}
	$inserted = count( $log['inserted'] );
	$skipped  = count( $log['skipped'] );
	echo '<div class="notice notice-success is-dismissible"><p>';
	echo '<strong>Anju Theme:</strong> 初期コンテンツの自動投入を実行しました。';
	echo ' 投入：<strong>' . esc_html( $inserted ) . '件</strong>／既存値ありのためスキップ：<strong>' . esc_html( $skipped ) . '件</strong>';
	echo '</p></div>';
	delete_transient( 'anju_migration_just_ran' );
}
add_action( 'admin_notices', 'anju_migration_admin_notice' );
