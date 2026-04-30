<?php
/**
 * Template Name: グループホームページ
 *
 * @package Anju
 */
get_header();

$pid = get_the_ID();

$hero_image       = anju_meta( 'grouphome_hero_image', $pid );
$hero_image_url   = anju_image_url( $hero_image, 'large' );
$gallery          = anju_meta( 'grouphome_gallery', $pid, array() );
$homes            = anju_meta( 'grouphome_homes', $pid, array(), 'complex' );

$section_lead     = anju_meta( 'grouphome_section_lead', $pid, '安心して穏やかに過ごせるホームを目指して' );
$section_desc     = anju_meta( 'grouphome_section_desc', $pid, '合同会社あんじゅでは、大田区内で知的障害や精神障害をお持ちの方のグループホーム（共同生活援助）を運営しています。地域や支援者の方々、医療職の方とも手を取り合い、利用者さまが安心して暮らせる環境づくりに取り組んでいます。' );

$sub_heading      = anju_meta( 'grouphome_subsection_heading', $pid, 'グループホームあんじゅ' );
$sub_heading_sub  = anju_meta( 'grouphome_subsection_heading_sub', $pid, '（大田区・4事業所）' );
$sub_intro        = anju_meta( 'grouphome_subsection_intro', $pid, '大田区内で知的障害や精神障害をお持ちの方のグループホーム（共同生活援助）を運営しています。' );
$lead_title       = anju_meta( 'grouphome_lead_title', $pid, '安心して穏やかに過ごせる、もう一つの居場所を目指して。' );
$lead_body        = anju_meta( 'grouphome_lead_body', $pid, '<p>利用者さまが安心して笑顔で暮らし、同時にスタッフも笑顔でいられることが、よりよい支援を生み、それが継続できるものと考えています。</p><p>共同生活を通じて、ひとりじゃない、一緒だったら何かできるかも…を探せる環境を用意していきたいと思います。</p>' );

$policy_text      = anju_meta( 'grouphome_policy_text', $pid, '私たちは、利用者さまが笑顔で暮らし、スタッフが健康的に働けることが、より良い福祉サービスを生み出すと信じています。グループホームという共同生活を通じて、ひとりじゃない、一緒だったら何かできるかも…を探せる環境を用意していきたいと考えています。' );
?>

<!-- ========== SERVICE HERO BANNER ========== -->
<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">group home</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<!-- ========== MAIN PHOTO ========== -->
<section class="svc-photo fade-in">
	<?php $bg = $hero_image_url ?: ANJU_THEME_URI . '/assets/images/grouphometop.jpg'; ?>
	<div class="svc-photo__image" style="background:linear-gradient(135deg, rgba(196,168,160,0.3), rgba(180,160,155,0.4)), url('<?php echo esc_url( $bg ); ?>') center/cover no-repeat;"></div>
</section>

<!-- ========== 事業概要 ========== -->
<section class="svc-nursing fade-in">
	<div class="container">
		<div class="svc-nursing__header">
			<div class="svc-nursing__label">
				<span class="svc-nursing__dot"></span>
				<span class="svc-nursing__label-text">グループホーム事業</span>
			</div>
			<p class="svc-nursing__lead"><?php echo esc_html( $section_lead ); ?></p>
		</div>
		<div class="svc-nursing__body">
			<?php if ( get_the_content() ) : ?>
				<?php the_content(); ?>
			<?php else : ?>
				<p class="svc-nursing__desc"><?php echo nl2br( esc_html( $section_desc ) ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- ========== 事業内容 (グループホーム) ========== -->
<section class="svc-grouphome fade-in" id="grouphome">
	<div class="container">
		<h3 class="svc-section__heading">
			<span class="svc-section__heading-mark">■</span><?php echo esc_html( $sub_heading ); ?>
			<?php if ( $sub_heading_sub ) : ?><span class="svc-section__heading-sub"><?php echo esc_html( $sub_heading_sub ); ?></span><?php endif; ?>
		</h3>
		<?php if ( $sub_intro ) : ?>
		<p class="svc-section__intro"><?php echo nl2br( esc_html( $sub_intro ) ); ?></p>
		<?php endif; ?>

		<div class="svc-section__lead-box">
			<?php if ( $lead_title ) : ?>
			<p class="svc-section__lead"><?php echo esc_html( $lead_title ); ?></p>
			<?php endif; ?>
			<?php echo wp_kses_post( $lead_body ); ?>
		</div>

		<div class="svc-homes">
			<?php
			// === 一時デバッグ: 確認後に削除してください ===
			if ( current_user_can( 'manage_options' ) ) {
				echo '<!-- ANJU DEBUG START -->';
				echo '<!-- pid=' . esc_html( (string) $pid ) . ' -->';
				echo '<!-- template=' . esc_html( (string) get_post_meta( $pid, '_wp_page_template', true ) ) . ' -->';
				echo '<!-- raw carbon_get_post_meta=' . esc_html( wp_json_encode( function_exists( 'carbon_get_post_meta' ) ? carbon_get_post_meta( $pid, 'grouphome_homes' ) : 'no-carbon' ) ) . ' -->';
				echo '<!-- homes_var=' . esc_html( wp_json_encode( $homes ) ) . ' -->';
				echo '<!-- branch=' . ( ( ! empty( $homes ) && is_array( $homes ) ) ? 'DYNAMIC' : 'FALLBACK' ) . ' -->';
				echo '<!-- ANJU DEBUG END -->';
			}
			?>
			<?php if ( ! empty( $homes ) && is_array( $homes ) ) : ?>
				<?php foreach ( $homes as $home ) :
					$name        = $home['name']        ?? '';
					$address     = $home['address']     ?? '';
					$type        = $home['type']        ?? '';
					$capacity    = $home['capacity']    ?? '';
					$gender      = $home['gender']      ?? '';
					$image_left  = $home['image_left']  ?? 0;
					$image_right = $home['image_right'] ?? 0;
					$left_url    = anju_image_url( $image_left, 'medium_large' );
					$right_url   = anju_image_url( $image_right, 'medium_large' );
					?>
					<article class="svc-home-card">
						<div class="svc-home-card__images">
							<div class="svc-home-card__image"<?php if ( $left_url ) : ?> style="background-image:url('<?php echo esc_url( $left_url ); ?>')"<?php endif; ?>></div>
							<div class="svc-home-card__image"<?php if ( $right_url ) : ?> style="background-image:url('<?php echo esc_url( $right_url ); ?>')"<?php endif; ?>></div>
						</div>
						<div class="svc-home-card__body">
							<h4 class="svc-home-card__name"><?php echo esc_html( $name ); ?></h4>
							<p class="svc-home-card__addr"><?php echo esc_html( $address ); ?></p>
							<?php if ( $type || $capacity || $gender ) : ?>
							<ul class="svc-home-card__meta">
								<?php if ( $type ) : ?>
								<li class="svc-home-card__meta-item"><span class="svc-home-card__meta-label">タイプ</span><span class="svc-home-card__meta-value"><?php echo esc_html( $type ); ?></span></li>
								<?php endif; ?>
								<?php if ( $capacity ) : ?>
								<li class="svc-home-card__meta-item"><span class="svc-home-card__meta-label">定員数</span><span class="svc-home-card__meta-value"><?php echo esc_html( $capacity ); ?></span></li>
								<?php endif; ?>
								<?php if ( $gender ) : ?>
								<li class="svc-home-card__meta-item"><span class="svc-home-card__meta-label">性別</span><span class="svc-home-card__meta-value"><?php echo esc_html( $gender ); ?></span></li>
								<?php endif; ?>
							</ul>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			<?php else : ?>
				<!-- フォールバック：管理画面で「ホーム一覧」を未設定の場合 -->
				<?php
				$default_homes = array(
					array( 'name' => 'あんじゅ大森南',  'addr' => '東京都大田区大森南3-29-10',          'type' => 'シェアハウスタイプ', 'capacity' => '定員9名',  'gender' => '男性',       'left' => 'grouphome-hero.jpg',             'right' => 'omori-living-2f.jpg' ),
					array( 'name' => 'あんじゅ大岡山①', 'addr' => '東京都大田区南千束3-3-14 1階',       'type' => 'シェアハウスタイプ', 'capacity' => '定員5名',  'gender' => '男性',       'left' => 'okayama12-exterior-01.jpg',     'right' => 'okayama1-living.jpg' ),
					array( 'name' => 'あんじゅ大岡山②', 'addr' => '東京都大田区南千束3-3-14 2階・3階', 'type' => 'シェアハウスタイプ', 'capacity' => '定員6名',  'gender' => '男性',       'left' => 'gh-washroom-01.jpg',            'right' => 'okayama2-room-01.jpg' ),
					array( 'name' => 'あんじゅ西蒲田',  'addr' => '東京都大田区西蒲田6-16-5',           'type' => 'アパートタイプ',     'capacity' => '定員10名', 'gender' => '男性・女性', 'left' => 'nishikamata-exterior-front.jpg', 'right' => 'nishikamata-kitchen-02.jpg' ),
				);
				foreach ( $default_homes as $home ) : ?>
					<article class="svc-home-card">
						<div class="svc-home-card__images">
							<div class="svc-home-card__image" style="background-image:url('<?php echo esc_url( ANJU_THEME_URI . '/assets/images/' . $home['left'] ); ?>')"></div>
							<div class="svc-home-card__image" style="background-image:url('<?php echo esc_url( ANJU_THEME_URI . '/assets/images/' . $home['right'] ); ?>')"></div>
						</div>
						<div class="svc-home-card__body">
							<h4 class="svc-home-card__name"><?php echo esc_html( $home['name'] ); ?></h4>
							<p class="svc-home-card__addr"><?php echo esc_html( $home['addr'] ); ?></p>
							<ul class="svc-home-card__meta">
								<li class="svc-home-card__meta-item"><span class="svc-home-card__meta-label">タイプ</span><span class="svc-home-card__meta-value"><?php echo esc_html( $home['type'] ); ?></span></li>
								<li class="svc-home-card__meta-item"><span class="svc-home-card__meta-label">定員数</span><span class="svc-home-card__meta-value"><?php echo esc_html( $home['capacity'] ); ?></span></li>
								<li class="svc-home-card__meta-item"><span class="svc-home-card__meta-label">性別</span><span class="svc-home-card__meta-value"><?php echo esc_html( $home['gender'] ); ?></span></li>
							</ul>
						</div>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- ========== PHOTO GALLERY ========== -->
<section class="svc-gallery fade-in">
	<div class="container">
		<div class="svc-gallery__header">
			<h2 class="svc-gallery__title">gallery</h2>
			<p class="svc-gallery__subtitle">日々の様子</p>
		</div>
		<div class="svc-gallery__grid">
			<div class="svc-gallery__track">
				<?php
				if ( ! empty( $gallery ) && is_array( $gallery ) ) {
					for ( $loop = 0; $loop < 2; $loop++ ) {
						foreach ( $gallery as $img ) {
							$url = anju_image_url( $img, 'medium_large' );
							if ( ! $url ) continue;
							echo '<div class="svc-gallery__item" style="background-image:url(\'' . esc_url( $url ) . '\')"></div>';
						}
					}
				} else {
					$fallbacks = array(
						'gh-cooking-02.jpg', 'gh-meal-04.jpg', 'gh-staff-group-03.jpg',
						'gh-allowance-support.jpg', 'meeting-06.jpg', 'gh-meal-01.jpg',
					);
					for ( $loop = 0; $loop < 2; $loop++ ) {
						foreach ( $fallbacks as $name ) {
							echo '<div class="svc-gallery__item" style="background-image:url(\'' . esc_url( ANJU_THEME_URI . '/assets/images/' . $name ) . '\')"></div>';
						}
					}
				}
				?>
			</div>
		</div>
	</div>
</section>

<!-- ========== OUR POLICY ========== -->
<section class="svc-policy fade-in">
	<div class="container">
		<div class="svc-policy__header">
			<h2 class="svc-policy__title">our policy</h2>
			<div class="svc-policy__title-line"></div>
		</div>
		<p class="svc-policy__text"><?php echo nl2br( esc_html( $policy_text ) ); ?></p>
	</div>
</section>

<?php get_template_part( 'template-parts/contact-cta' ); ?>

<?php get_footer(); ?>
