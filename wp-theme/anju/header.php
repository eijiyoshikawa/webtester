<?php
/**
 * Header Template
 *
 * @package Anju
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	<!-- ========== BACKGROUND ANIMATION ========== -->
	<div class="bg-anim" aria-hidden="true">
		<span class="bg-anim__blob bg-anim__blob--1"></span>
		<span class="bg-anim__blob bg-anim__blob--2"></span>
		<span class="bg-anim__blob bg-anim__blob--3"></span>
		<span class="bg-anim__blob bg-anim__blob--4"></span>
	</div>

	<!-- ========== HEADER ========== -->
	<header class="header" id="header">
		<div class="header__inner">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__logo">
				<?php
				$logo = anju_option( 'site_logo' );
				$logo_url = anju_image_url( $logo, 'medium' );
				$company  = anju_option( 'site_company_jp', '合同会社あんじゅ' );
				if ( $logo_url ) :
				?>
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $company ); ?>" class="header__logo-img">
				<?php else : ?>
				<img src="<?php echo esc_url( ANJU_THEME_URI . '/assets/images/logo-company.png' ); ?>" alt="<?php echo esc_attr( $company ); ?>" class="header__logo-img">
				<?php endif; ?>
			</a>
			<nav class="header__nav" id="globalNav">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__nav-item header__nav-link<?php echo is_front_page() ? ' is-active' : ''; ?>">ホーム</a>
				<div class="header__nav-item header__nav-dropdown" data-dropdown>
					<span class="header__nav-trigger">サービス</span>
					<div class="header__nav-submenu">
						<a href="<?php echo esc_url( home_url( '/service/grouphome/' ) ); ?>" class="header__nav-subitem">グループホーム</a>
						<a href="<?php echo esc_url( home_url( '/service/houkan/' ) ); ?>" class="header__nav-subitem">訪問看護</a>
					</div>
				</div>
				<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="header__nav-item header__nav-link<?php echo ( is_post_type_archive( 'news' ) || is_singular( 'news' ) ) ? ' is-active' : ''; ?>">お知らせ</a>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="header__nav-item header__nav-link<?php echo ( is_home() || is_singular( 'post' ) ) ? ' is-active' : ''; ?>">ブログ</a>
				<a href="<?php echo esc_url( anju_option( 'site_contact_url', home_url( '/contact/' ) ) ); ?>" class="header__nav-item header__nav-link<?php echo is_page( 'contact' ) ? ' is-active' : ''; ?>">お問い合わせ</a>
			</nav>
			<button class="header__hamburger" id="hamburger" aria-label="メニューを開く">
				<span></span><span></span><span></span>
			</button>
		</div>
	</header>

	<!-- ========== SNS SIDEBAR ========== -->
	<div class="sidebar-sns">
		<?php $instagram = anju_option( 'site_sns_instagram' ); if ( $instagram ) : ?>
		<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="sidebar-sns__item" aria-label="Instagram">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/></svg>
		</a>
		<?php endif; ?>
	</div>
