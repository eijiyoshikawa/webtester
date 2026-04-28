<?php
/**
 * Template Name: お問い合わせ完了
 *
 * @package Anju
 */
get_header();
?>

<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">thank you</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<section class="cnt-page">
	<div class="container">
		<div class="cnt-page__header fade-in">
			<h1 class="cnt-page__title">thanks</h1>
			<p class="cnt-page__subtitle">送信完了</p>
		</div>
		<div class="cnt-page__form-card fade-in" style="max-width:720px;margin:0 auto;text-align:center;">
			<?php if ( get_the_content() ) : ?>
				<?php the_content(); ?>
			<?php else : ?>
				<p style="padding:40px 0;line-height:2;">
					お問い合わせいただきありがとうございました。<br>
					内容を確認のうえ、担当者より折り返しご連絡いたします。<br>
					今しばらくお待ちください。
				</p>
				<p style="margin-top:32px;">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-cta">
						<span class="btn-cta__label">トップページへ戻る</span>
						<span class="btn-cta__line"></span>
						<span class="btn-cta__dot"></span>
					</a>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
