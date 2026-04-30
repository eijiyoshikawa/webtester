<?php
/**
 * Template Name: 訪問看護ページ
 *
 * @package Anju
 */
get_header();

$pid = get_the_ID();

$hero_image       = anju_meta( 'houkan_hero_image', $pid );
$hero_image_url   = anju_image_url( $hero_image, 'large' );

$section_lead     = anju_meta( 'houkan_section_lead', $pid, '身近な医療の相談相手を目指して' );
$section_desc     = anju_meta( 'houkan_section_desc', $pid, '合同会社あんじゅでは、大田区山王を拠点に訪問看護ステーションを運営しています。医師や薬剤師など関係者の方々、また利用者さまを日々サポートされているご家族や支援者の方々とも密に連携を取り、利用者さまが安心して暮らせる環境づくりに取り組んでいます。' );

$sub_heading      = anju_meta( 'houkan_subsection_heading', $pid, '訪問看護ステーションあんじゅ' );
$sub_heading_sub  = anju_meta( 'houkan_subsection_heading_sub', $pid, '（大田区山王）' );
$lead_title       = anju_meta( 'houkan_lead_title', $pid, '身近な医療の相談相手。そばで支える人にも寄り添う訪問看護を。' );
$lead_body        = anju_meta( 'houkan_lead_body', $pid, '<p>心と体の相談に乗り、医師と連携して安心した地域生活をサポートします。</p>' );

$strength_heading = anju_meta( 'houkan_strength_heading', $pid, '＜あんじゅ訪看の強み＞' );
$strength_lead    = anju_meta( 'houkan_strength_lead', $pid, '障害者グループホームを運営する訪問看護ステーションだからこその強みがあります。看護師全員が障害者グループホームでの世話人業務経験者です。' );
$strength_items   = anju_meta( 'houkan_strength_items', $pid, array(), 'complex' );

$sidebar_blocks   = anju_meta( 'houkan_sidebar_blocks', $pid, array(), 'complex' );

$price_pdf        = anju_meta( 'houkan_price_pdf', $pid );
$price_title      = anju_meta( 'houkan_price_title', $pid );
$price_desc       = anju_meta( 'houkan_price_desc', $pid );
$service_pdf      = anju_meta( 'houkan_service_pdf', $pid );
$service_title    = anju_meta( 'houkan_service_title', $pid );
$service_desc     = anju_meta( 'houkan_service_desc', $pid );

$gallery          = anju_meta( 'houkan_gallery', $pid, array() );
$policy_text      = anju_meta( 'houkan_policy_text', $pid, '私たちは、利用者さまが笑顔で暮らし、スタッフが健康的に働けることが、より良い医療福祉サービスを生み出すと信じています。心と体の健康を支え、地域での暮らしに寄り添う訪問看護を提供していきたいと考えています。' );
?>

<!-- ========== SERVICE HERO BANNER ========== -->
<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">home nursing</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<!-- ========== MAIN PHOTO ========== -->
<section class="svc-photo fade-in">
	<?php $bg = $hero_image_url ?: ANJU_THEME_URI . '/assets/images/houkan-medicine-01.jpg'; ?>
	<div class="svc-photo__image" style="background:linear-gradient(135deg, rgba(196,168,160,0.3), rgba(180,160,155,0.4)), url('<?php echo esc_url( $bg ); ?>') center/cover no-repeat;"></div>
</section>

<!-- ========== 事業概要 ========== -->
<section class="svc-nursing fade-in">
	<div class="container">
		<div class="svc-nursing__header">
			<div class="svc-nursing__label">
				<span class="svc-nursing__dot"></span>
				<span class="svc-nursing__label-text">訪問看護事業</span>
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

<!-- ========== 事業内容 (訪問看護) ========== -->
<section class="svc-houkan fade-in" id="houkan">
	<div class="container">
		<div class="svc-details__grid">
			<div class="svc-details__main">
				<h3 class="svc-section__heading">
					<span class="svc-section__heading-mark">■</span><?php echo esc_html( $sub_heading ); ?>
					<?php if ( $sub_heading_sub ) : ?><span class="svc-section__heading-sub"><?php echo esc_html( $sub_heading_sub ); ?></span><?php endif; ?>
				</h3>

				<div class="svc-section__lead-box">
					<?php if ( $lead_title ) : ?>
					<p class="svc-section__lead"><?php echo esc_html( $lead_title ); ?></p>
					<?php endif; ?>
					<?php echo wp_kses_post( $lead_body ); ?>
				</div>

				<div class="svc-strength">
					<?php if ( $strength_heading ) : ?>
					<h4 class="svc-strength__heading"><?php echo esc_html( $strength_heading ); ?></h4>
					<?php endif; ?>
					<?php if ( $strength_lead ) : ?>
					<p class="svc-strength__lead"><?php echo nl2br( esc_html( $strength_lead ) ); ?></p>
					<?php endif; ?>
					<?php
					$items = ( ! empty( $strength_items ) && is_array( $strength_items ) )
						? array_filter( array_map( fn( $row ) => $row['text'] ?? '', $strength_items ) )
						: array(
							'おひとりおひとりの障害特性や個別支援計画を理解し、自立支援や社会参加支援のサポートをいたします。',
							'利用者様の日中活動を制限しないよう、柔軟に訪問時間を調整いたします。',
							'支援者様にもしっかり寄り添いながら、最善の看護支援を目指します。',
						);
					if ( ! empty( $items ) ) : ?>
					<ul class="svc-strength__list">
						<?php foreach ( $items as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>
			</div>
			<div class="svc-details__sidebar">
				<?php
				$blocks = ( ! empty( $sidebar_blocks ) && is_array( $sidebar_blocks ) )
					? $sidebar_blocks
					: array(
						array( 'heading' => '在籍スタッフ数',     'body' => "看護師：4名\n地域連携担当：2名" ),
						array( 'heading' => '営業日及び営業時間', 'body' => "9:00-18:00\n緊急対応 24時間 365日" ),
						array( 'heading' => '訪問看護提供地域',   'body' => "大田区\n他、相談に応じて" ),
					);
				foreach ( $blocks as $block ) :
					$heading = $block['heading'] ?? '';
					$body    = $block['body']    ?? '';
					if ( ! $heading && ! $body ) continue;
					?>
					<div class="svc-details__sidebar-block">
						<?php if ( $heading ) : ?>
						<h4 class="svc-details__sidebar-heading"><?php echo esc_html( $heading ); ?></h4>
						<?php endif; ?>
						<?php
						$lines = preg_split( '/\r\n|\r|\n/', (string) $body );
						foreach ( $lines as $line ) :
							$line = trim( $line );
							if ( $line === '' ) continue;
							?>
							<p class="svc-details__sidebar-text"><?php echo esc_html( $line ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- ========== 料金表・サービス詳細 ========== -->
<section class="svc-fees fade-in" id="fees">
	<div class="container">
		<div class="svc-fees__header">
			<h2 class="svc-fees__title">price list</h2>
			<p class="svc-fees__subtitle">料金表・サービス詳細</p>
		</div>
		<p class="svc-fees__lead">訪問看護ステーションあんじゅの料金表およびサービス詳細は、以下のPDFよりご覧いただけます。</p>
		<div class="svc-fees__cards">
			<?php
			$cards = array(
				array(
					'pdf'   => $price_pdf,
					'title' => $price_title ?: '訪問看護 料金表',
					'desc'  => $price_desc ?: '基本料金・加算項目をまとめた料金一覧です。',
				),
				array(
					'pdf'   => $service_pdf,
					'title' => $service_title ?: 'サービス詳細',
					'desc'  => $service_desc ?: '提供サービスの内容・対象・利用方法をご案内します。',
				),
			);
			foreach ( $cards as $card ) :
				$pdf_url = anju_image_url( $card['pdf'] );
				if ( ! $pdf_url ) continue;
				?>
				<a href="<?php echo esc_url( $pdf_url ); ?>" class="svc-fees__card" target="_blank" rel="noopener">
					<div class="svc-fees__card-icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
							<path d="M14 2v6h6" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
							<path d="M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
						</svg>
					</div>
					<div class="svc-fees__card-body">
						<span class="svc-fees__card-label">PDF</span>
						<h3 class="svc-fees__card-title"><?php echo esc_html( $card['title'] ); ?></h3>
						<p class="svc-fees__card-desc"><?php echo esc_html( $card['desc'] ); ?></p>
					</div>
					<span class="svc-fees__card-arrow" aria-hidden="true">→</span>
				</a>
			<?php endforeach; ?>
		</div>
		<p class="svc-fees__note">※料金・サービス内容に関するご質問は、<a href="<?php echo esc_url( anju_option( 'site_contact_url', home_url( '/contact/' ) ) ); ?>">お問い合わせフォーム</a>よりお気軽にご連絡ください。</p>
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
						'houkan-medicine-01.jpg', 'houkan-medicine-03.jpg', 'houkan-bloodpressure.jpg',
						'houkan-staff-all-01.jpg', 'meeting-allmember-03.jpg', 'meeting-04.jpg',
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
