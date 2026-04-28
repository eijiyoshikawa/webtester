<?php
/**
 * Contact CTA Section（全ページ共通）
 *
 * @package Anju
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$contact_url = anju_option( 'site_contact_url', home_url( '/contact/' ) );
?>
<section class="contact-section" id="contact-section">
	<div class="container">
		<div class="contact-section__inner fade-in">
			<h2 class="contact-section__heading">contact</h2>
			<p class="contact-section__subheading">お問い合わせ</p>
			<p class="contact-section__description">弊社へのご質問（会社情報、サービス等）は、<br>こちらからお問い合わせください。</p>
			<div class="contact-section__btn">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-cta btn-cta--white">
					<span class="btn-cta__label">お問い合わせフォームはこちら</span>
					<span class="btn-cta__line"></span>
					<span class="btn-cta__dot"></span>
				</a>
			</div>
		</div>
	</div>
</section>
