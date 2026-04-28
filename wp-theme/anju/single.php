<?php
/**
 * Single Post（ブログ記事詳細）
 *
 * @package Anju
 */
get_header();
?>

<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">blog</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<section class="privacy-page">
	<div class="container">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="privacy-page__header fade-in">
				<p class="privacy-page__subtitle"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></p>
				<h1 class="privacy-page__title" style="font-family:'Noto Sans JP',sans-serif;font-size:1.6rem;"><?php the_title(); ?></h1>
			</div>
			<div class="privacy-page__body fade-in">
				<?php if ( has_post_thumbnail() ) : ?>
					<div style="margin-bottom:32px;">
						<?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;border-radius:12px;' ) ); ?>
					</div>
				<?php endif; ?>
				<?php the_content(); ?>
				<p style="margin-top:60px;">
					<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="cta-link">
						<span class="cta-link__circle">←</span>
						<span class="cta-link__text">ブログ一覧へ戻る</span>
					</a>
				</p>
			</div>
		<?php endwhile; endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/contact-cta' ); ?>

<?php get_footer(); ?>
