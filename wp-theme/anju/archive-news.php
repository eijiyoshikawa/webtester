<?php
/**
 * Archive: お知らせ一覧
 *
 * @package Anju
 */
get_header();
?>

<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">news</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<section class="news-page">
	<div class="container">
		<div class="news-page__header fade-in">
			<h1 class="news-page__title">news</h1>
			<p class="news-page__subtitle">お知らせ</p>
		</div>

		<div class="news-page__list fade-in">
			<?php if ( have_posts() ) :
				while ( have_posts() ) : the_post(); ?>
					<article class="news-page__item">
						<time class="news-page__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
						<p class="news-page__item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
					</article>
				<?php endwhile;
			else : ?>
				<p style="text-align:center;color:#999;padding:60px 0;">お知らせはまだありません</p>
			<?php endif; ?>
		</div>

		<?php
		the_posts_pagination( array(
			'mid_size'  => 2,
			'prev_text' => '前へ',
			'next_text' => '次へ',
		) );
		?>
	</div>
</section>

<?php get_template_part( 'template-parts/contact-cta' ); ?>

<?php get_footer(); ?>
