<?php
/**
 * Template Name: 運用マニュアル（社内用）
 *
 * 本テンプレートを適用する固定ページの「公開状態」を**パスワード保護**に
 * 設定してください。WordPress 標準のパスワード入力画面が自動表示されます。
 *
 * 推奨運用：
 * - メニューには表示しない
 * - URL を共有する関係者のみアクセス可能
 *
 * @package Anju
 */

// パスワード保護ページなのでキャッシュさせない（デバイスにより表示が不安定になるのを防止）
if ( ! defined( 'DONOTCACHEPAGE' ) ) {
	define( 'DONOTCACHEPAGE', true );
}
if ( ! defined( 'DONOTCACHEDB' ) ) {
	define( 'DONOTCACHEDB', true );
}
if ( ! defined( 'DONOTMINIFY' ) ) {
	define( 'DONOTMINIFY', true );
}
if ( ! defined( 'DONOTCDN' ) ) {
	define( 'DONOTCDN', true );
}
nocache_headers();

get_header();
?>

<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">manual</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<section class="privacy-page">
	<div class="container">
		<div class="privacy-page__header fade-in">
			<h1 class="privacy-page__title">operations manual</h1>
			<p class="privacy-page__subtitle">運用マニュアル（社内用）</p>
		</div>

		<div class="privacy-page__body fade-in">
			<?php if ( post_password_required() ) : ?>
				<?php echo get_the_password_form(); ?>
			<?php else : ?>
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						the_content();
					endwhile;
				else :
					echo '<p>マニュアルの内容を編集画面から登録してください。</p>';
				endif;
				?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
