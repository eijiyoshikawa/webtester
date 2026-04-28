<?php
/**
 * 404 Error Page
 *
 * @package Anju
 */
get_header();
?>

<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">404</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<section class="privacy-page">
	<div class="container">
		<div class="privacy-page__header fade-in">
			<h1 class="privacy-page__title">page not found</h1>
			<p class="privacy-page__subtitle">ページが見つかりません</p>
		</div>
		<div class="privacy-page__body fade-in" style="text-align:center;">
			<p style="padding:40px 0;line-height:2;">
				お探しのページは見つかりませんでした。<br>
				URLが間違っているか、ページが移動・削除された可能性があります。
			</p>
			<p style="margin-top:32px;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-cta">
					<span class="btn-cta__label">トップページへ戻る</span>
					<span class="btn-cta__line"></span>
					<span class="btn-cta__dot"></span>
				</a>
			</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
