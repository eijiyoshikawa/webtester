<?php
/**
 * Footer Template
 *
 * @package Anju
 */
?>
	<!-- ========== FOOTER ========== -->
	<footer class="footer">
		<div class="footer__brand">
			<?php
			$logo = anju_option( 'site_logo' );
			$logo_url = anju_image_url( $logo, 'medium' );
			$company  = anju_option( 'site_company_jp', '合同会社あんじゅ' );
			$company_en = anju_option( 'site_company_en', 'Anju LLC.' );
			?>
			<img src="<?php echo esc_url( $logo_url ?: ANJU_THEME_URI . '/assets/images/logo-company.png' ); ?>" alt="<?php echo esc_attr( $company ); ?>" class="footer__logo-img">
			<span class="footer__name-jp"><?php echo esc_html( $company ); ?></span>
			<span class="footer__name-en"><?php echo esc_html( $company_en ); ?></span>
		</div>
		<nav class="footer__nav">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__nav-item">ホーム</a>
			<a href="<?php echo esc_url( home_url( '/service/grouphome/' ) ); ?>" class="footer__nav-item">グループホーム</a>
			<a href="<?php echo esc_url( home_url( '/service/houkan/' ) ); ?>" class="footer__nav-item">訪問看護</a>
			<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="footer__nav-item">お知らせ</a>
			<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="footer__nav-item">ブログ</a>
			<a href="<?php echo esc_url( anju_option( 'site_contact_url', home_url( '/contact/' ) ) ); ?>" class="footer__nav-item">お問い合わせ</a>
			<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="footer__nav-item">プライバシーポリシー</a>
		</nav>
		<p class="footer__copyright"><?php echo esc_html( anju_option( 'site_copyright', '© Anju LLC. All Rights Reserved.' ) ); ?></p>
	</footer>

	<!-- Back to Top -->
	<a href="#home" class="back-to-top" id="backToTop" aria-label="ページトップへ戻る">
		<svg viewBox="0 0 24 24" fill="none"><path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
	</a>

	<?php wp_footer(); ?>
</body>
</html>
