<?php
/**
 * Template Name: お問い合わせページ
 *
 * Contact Form 7 のショートコードを利用する想定。
 * WP管理画面で「お問い合わせ」固定ページの本文に [contact-form-7 id="..."] を貼り付ける運用、
 * または ACF で contact_cf7_shortcode を用意する運用、いずれもOK。
 *
 * @package Anju
 */
get_header();

$cf7_shortcode = anju_meta( 'contact_cf7_shortcode' );
if ( ! $cf7_shortcode ) {
	$cf7_shortcode = get_post_meta( get_the_ID(), 'cf7_shortcode', true );
}
?>

<!-- ========== HERO BANNER ========== -->
<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">contact</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<!-- ========== CONTACT PAGE ========== -->
<section class="cnt-page">
	<div class="container">
		<div class="cnt-page__header fade-in">
			<h1 class="cnt-page__title">contact</h1>
			<p class="cnt-page__subtitle">お問い合わせ</p>
		</div>

		<div class="cnt-page__grid fade-in">
			<!-- Form -->
			<div class="cnt-page__form-card">
				<p class="cnt-page__intro">この度は、弊社にご興味を持っていただきありがとうございます。<br>お手数ですが、下記フォームよりお問い合わせください。</p>
				<hr class="cnt-page__divider">

				<?php if ( $cf7_shortcode ) : ?>
					<?php echo do_shortcode( $cf7_shortcode ); ?>
				<?php elseif ( get_the_content() ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<!-- Contact Form 7 ショートコードを設定するか、ページ本文に直接 [contact-form-7 id="..."] を貼り付けてください -->
					<p style="padding:40px 0;text-align:center;color:#999;">
						※ Contact Form 7 のショートコードを設定してください。<br>
						管理画面 → お問い合わせ固定ページ → 本文に <code>[contact-form-7 id="..."]</code>
					</p>
				<?php endif; ?>

				<?php $instagram = anju_option( 'site_sns_instagram' ); if ( $instagram ) : ?>
				<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="cnt-form__sp-instagram" aria-label="Instagramをフォロー">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/></svg>
					<span>Instagramをフォロー</span>
				</a>
				<?php endif; ?>
			</div>

			<!-- Sidebar Info -->
			<div class="cnt-page__sidebar">
				<div class="cnt-page__sidebar-logo">
					<?php
					$logo = anju_option( 'site_logo' );
					$logo_url = anju_image_url( $logo, 'medium' ) ?: ANJU_THEME_URI . '/assets/images/logo-company.png';
					?>
					<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( anju_option( 'site_company_jp', '合同会社あんじゅ' ) ); ?>" style="height:60px;width:auto;">
					<div class="cnt-page__sidebar-name">
						<span><?php echo esc_html( anju_option( 'site_company_jp', '合同会社 あんじゅ' ) ); ?></span>
						<span class="cnt-page__sidebar-name-en"><?php echo esc_html( anju_option( 'site_company_en', 'Anju LLC.' ) ); ?></span>
					</div>
				</div>
				<hr class="cnt-page__divider">
				<p class="cnt-page__sidebar-follow">フォローはこちら</p>
				<div class="cnt-page__sidebar-sns">
					<?php $instagram = anju_option( 'site_sns_instagram' ); if ( $instagram ) : ?>
					<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="cnt-page__sidebar-sns-link" aria-label="Instagram">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/></svg>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/contact-cta' ); ?>

<?php get_footer(); ?>
