<?php
/**
 * Blog Posts Index (home.php)
 *
 * WP標準の post 一覧。「投稿ページ」を固定ページに割り当てた場合に使用される。
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

<section class="blog-page">
	<div class="container">
		<div class="blog-page__header fade-in">
			<h1 class="blog-page__title">blog</h1>
			<p class="blog-page__subtitle">ブログ</p>
		</div>

		<div class="blog-page__grid fade-in">
			<?php if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					$thumb_url = get_the_post_thumbnail_url( null, 'medium' );
					if ( ! $thumb_url ) {
						$thumb_url = ANJU_THEME_URI . '/assets/images/grouphome-hero.jpg';
					}
					?>
					<a href="<?php the_permalink(); ?>" class="blog-card">
						<div class="blog-card__image" style="background-image:url('<?php echo esc_url( $thumb_url ); ?>');"></div>
						<h2 class="blog-card__title"><?php the_title(); ?></h2>
						<time class="blog-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
					</a>
				<?php endwhile;
			else : ?>
				<p style="grid-column:1 / -1;text-align:center;color:#999;padding:60px 0;">記事はまだありません</p>
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
