<?php
/**
 * Search Results
 *
 * @package Anju
 */
get_header();
?>

<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">search</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<section class="news news--archive" style="padding-top:80px;padding-bottom:80px;">
	<div class="container">
		<div class="news__header fade-in">
			<span class="news__label">search</span>
			<span class="news__label-divider">|</span>
			<span class="news__label-jp">「<?php echo esc_html( get_search_query() ); ?>」の検索結果</span>
		</div>
		<ul class="news__list fade-in">
			<?php if ( have_posts() ) :
				while ( have_posts() ) : the_post(); ?>
					<li class="news__item">
						<time class="news__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
						<p class="news__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
					</li>
				<?php endwhile;
			else : ?>
				<li class="news__item"><p class="news__title">該当する記事はありませんでした</p></li>
			<?php endif; ?>
		</ul>
		<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
	</div>
</section>

<?php get_footer(); ?>
