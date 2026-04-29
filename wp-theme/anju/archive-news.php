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

<section class="news news--archive" style="padding-top:80px;padding-bottom:80px;">
	<div class="container">
		<div class="news__header fade-in">
			<span class="news__label">news</span>
			<span class="news__label-divider">|</span>
			<span class="news__label-jp">お知らせ</span>
		</div>
		<ul class="news__list fade-in">
			<?php if ( have_posts() ) :
				while ( have_posts() ) : the_post(); ?>
					<li class="news__item">
						<time class="news__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
						<p class="news__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
						<span class="news__arrow">
							<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="1"/><path d="M10 8l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</span>
					</li>
				<?php endwhile;
			else : ?>
				<li class="news__item">
					<p class="news__title">お知らせはまだありません</p>
				</li>
			<?php endif; ?>
		</ul>

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
