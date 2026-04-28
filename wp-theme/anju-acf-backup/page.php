<?php
/**
 * Default Page Template
 *
 * 専用テンプレートが指定されていない固定ページのフォールバック。
 *
 * @package Anju
 */
get_header();
?>

<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title"><?php the_title(); ?></h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<section class="privacy-page">
	<div class="container">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="privacy-page__header fade-in">
				<h1 class="privacy-page__title" style="font-family:'Noto Sans JP',sans-serif;font-size:1.6rem;"><?php the_title(); ?></h1>
			</div>
			<div class="privacy-page__body fade-in">
				<?php if ( post_password_required() ) : ?>
					<?php echo get_the_password_form(); ?>
				<?php else : ?>
					<?php the_content(); ?>
				<?php endif; ?>
			</div>
		<?php endwhile; endif; ?>
	</div>
</section>

<?php get_footer(); ?>
