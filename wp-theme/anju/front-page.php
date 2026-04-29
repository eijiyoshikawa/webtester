<?php
/**
 * Template Name: Home
 * Front page (home)
 *
 * @package Anju
 */
get_header();

$pid = get_the_ID();

// Carbon Fields からフィールド取得（未導入時は空文字を返す anju_meta ヘルパー経由）
$hero_slides    = anju_meta( 'home_hero_slides', $pid, array() );
$hero_title     = anju_meta( 'home_hero_title', $pid );
$hero_subtitle  = anju_meta( 'home_hero_subtitle', $pid );
$philo_large    = anju_meta( 'home_philosophy_image_large', $pid );
$philo_left     = anju_meta( 'home_philosophy_image_left', $pid );
$philo_right    = anju_meta( 'home_philosophy_image_right', $pid );
$philo_lead     = anju_meta( 'home_philosophy_lead', $pid );
$philo_body     = anju_meta( 'home_philosophy_body', $pid );
$message_photo  = anju_meta( 'home_message_photo', $pid );
$message_body   = anju_meta( 'home_message_body', $pid );
$message_signer = anju_meta( 'home_message_signer', $pid );
$logo_grouphome = anju_meta( 'home_logo_grouphome', $pid );
$logo_houkan    = anju_meta( 'home_logo_houkan', $pid );

// 画像URLに変換
$philo_large_url    = anju_image_url( $philo_large, 'large' );
$philo_left_url     = anju_image_url( $philo_left, 'medium' );
$philo_right_url    = anju_image_url( $philo_right, 'medium' );
$logo_grouphome_url = anju_image_url( $logo_grouphome, 'medium' );
$logo_houkan_url    = anju_image_url( $logo_houkan, 'medium' );
$message_photo_url  = anju_image_url( $message_photo, 'large' );

$company = anju_option( 'site_company_jp', '合同会社あんじゅ' );
?>

<!-- ========== HERO ========== -->
<section class="hero" id="home">
	<div class="hero__slideshow">
		<?php if ( ! empty( $hero_slides ) && is_array( $hero_slides ) ) : ?>
			<?php foreach ( $hero_slides as $i => $slide ) :
				$url = anju_image_url( $slide, 'full' );
				if ( ! $url ) continue; ?>
				<div class="hero__slide<?php echo 0 === $i ? ' hero__slide--active' : ''; ?>" style="background-image:url('<?php echo esc_url( $url ); ?>')"></div>
			<?php endforeach; ?>
		<?php else : ?>
			<?php
			// フォールバック：Carbon Fields 未入力時は同梱画像でスライドショー
			$default_slides = array(
				'grouphome-hero.jpg',
				'gh-room-private-01.jpg',
				'nishikamata-exterior-front.jpg',
				'gh-washroom-01.jpg',
				'houkan-bloodpressure-02.jpg',
				'gh-allowance-support.jpg',
			);
			foreach ( $default_slides as $i => $name ) : ?>
				<div class="hero__slide<?php echo 0 === $i ? ' hero__slide--active' : ''; ?>" style="background-image:url('<?php echo esc_url( ANJU_THEME_URI . '/assets/images/' . $name ); ?>')"></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="hero__overlay"></div>
	<div class="hero__content">
		<h1 class="hero__title fade-in"><?php echo esc_html( $hero_title ?: 'ひとつでも多くの笑顔に出会うために' ); ?></h1>
		<p class="hero__subtitle fade-in fade-in--delay1"><?php echo wp_kses( nl2br( $hero_subtitle ?: "確かな支援で今日を支え、\n明日の笑顔をつなぐ" ), array( 'br' => array() ) ); ?></p>
		<div class="hero__logo fade-in fade-in--delay2">
			<span class="hero__logo-text">anju</span>
		</div>
		<p class="hero__logo-sub fade-in fade-in--delay2" style="color:rgba(255,255,255,0.7);font-size:0.8rem;letter-spacing:0.12em;margin-top:8px;"><?php echo esc_html( anju_option( 'site_company_en', 'Anju LLC.' ) ); ?></p>
	</div>
</section>

<!-- ========== PHILOSOPHY ========== -->
<section class="philosophy" id="philosophy">
	<div class="container">
		<div class="philosophy__header">
			<h2 class="philosophy__label">philosophy</h2>
			<p class="philosophy__subtitle">理念</p>
		</div>
		<div class="philosophy__body fade-in">
			<div class="philosophy__text">
				<?php if ( $philo_lead ) : ?>
				<p class="philosophy__lead"><?php echo wp_kses( nl2br( $philo_lead ), array( 'br' => array() ) ); ?></p>
				<?php else : ?>
				<p class="philosophy__lead">確かな支援の先にある、<br>関わる「すべての人」の笑顔のために。</p>
				<?php endif; ?>
				<?php if ( $philo_body ) : ?>
					<?php echo wp_kses_post( wpautop( $philo_body ) ); ?>
				<?php else : ?>
				<p>利用者様とご家族の穏やかな日常を支えることはもちろん、働くスタッフも笑顔でいられる環境をつくること。それが私たちの理念です。</p>
				<p>今日だけでなく、明日も明後日もその笑顔を守っていくために、枠にとらわれることなく、地域や様々な分野の方と手をつなぎ、本当に必要とされる確かな支援体制を創り上げていきたいと考えています。</p>
				<?php endif; ?>
			</div>
			<div class="philosophy__images">
				<div class="philosophy__image philosophy__image--large"<?php if ( $philo_large_url ) : ?> style="background-image:url('<?php echo esc_url( $philo_large_url ); ?>')"<?php endif; ?>></div>
				<div class="philosophy__image"<?php if ( $philo_left_url ) : ?> style="background-image:url('<?php echo esc_url( $philo_left_url ); ?>')"<?php endif; ?>></div>
				<div class="philosophy__image"<?php if ( $philo_right_url ) : ?> style="background-image:url('<?php echo esc_url( $philo_right_url ); ?>')"<?php endif; ?>></div>
			</div>
		</div>
	</div>
</section>

<!-- ========== NEWS ========== -->
<section class="news" id="news">
	<div class="container">
		<div class="news__header fade-in">
			<span class="news__label">news</span>
			<span class="news__label-divider">|</span>
			<span class="news__label-jp">お知らせ</span>
		</div>
		<ul class="news__list fade-in">
			<?php
			$news_query = new WP_Query( array(
				'post_type'      => 'news',
				'posts_per_page' => 5,
				'post_status'    => 'publish',
			) );
			if ( $news_query->have_posts() ) :
				while ( $news_query->have_posts() ) : $news_query->the_post();
				?>
				<li class="news__item">
					<time class="news__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
					<p class="news__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
					<span class="news__arrow">
						<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="1"/><path d="M10 8l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
				</li>
				<?php
				endwhile;
				wp_reset_postdata();
			else : ?>
				<li class="news__item">
					<time class="news__date"><?php echo esc_html( gmdate( 'Y.m.d' ) ); ?></time>
					<p class="news__title">お知らせはまだありません</p>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</section>

<!-- ========== OUR SERVICE ========== -->
<section class="our-service" id="service">
	<div class="container">
		<div class="our-service__inner fade-in">
			<p class="our-service__label">our service</p>
			<p class="our-service__subtitle">事業内容</p>
			<h3 class="our-service__heading">グループホーム事業と<br>訪問看護事業で<br>地域の暮らしを支える</h3>
		</div>
		<div class="our-service__logo-bg">
			<span class="our-service__logo-bg-text">anju</span>
		</div>
	</div>
</section>

<!-- ========== SERVICE DETAIL ========== -->
<section class="service-detail">
	<div class="container">
		<div class="service-detail__inner fade-in">
			<div class="service-detail__content">
				<span class="service-detail__label">グループホーム事業</span>
				<p class="service-detail__subtitle">グループホームあんじゅ</p>
				<p class="service-detail__text">安心して穏やかに過ごせる、もう一つの居場所を目指して。<br>大田区内で知的障害や精神障害をお持ちの方のグループホーム（共同生活援助）を運営しています。</p>
				<div class="service-detail__cta">
					<a href="<?php echo esc_url( home_url( '/service/grouphome/' ) ); ?>" class="cta-link">
						<span class="cta-link__circle">→</span>
						<span class="cta-link__text">詳しくはこちら</span>
					</a>
				</div>
			</div>
			<div class="service-detail__image service-detail__image--logo service-detail__image--grouphome" aria-label="グループホーム事業ロゴ"<?php if ( $logo_grouphome_url ) : ?> style="background-image:url('<?php echo esc_url( $logo_grouphome_url ); ?>')"<?php endif; ?>></div>
		</div>
		<div class="service-detail__inner fade-in">
			<div class="service-detail__content">
				<span class="service-detail__label">訪問看護事業</span>
				<p class="service-detail__subtitle">訪問看護ステーションあんじゅ</p>
				<p class="service-detail__text">身近な医療の相談相手。そばで支える人にも寄り添う訪問看護を。<br>心と体の相談に乗り、医師と連携して安心した地域生活をサポートします。</p>
				<div class="service-detail__cta">
					<a href="<?php echo esc_url( home_url( '/service/houkan/' ) ); ?>" class="cta-link">
						<span class="cta-link__circle">→</span>
						<span class="cta-link__text">詳しくはこちら</span>
					</a>
				</div>
			</div>
			<div class="service-detail__image service-detail__image--logo service-detail__image--houkan" aria-label="訪問看護事業ロゴ"<?php if ( $logo_houkan_url ) : ?> style="background-image:url('<?php echo esc_url( $logo_houkan_url ); ?>')"<?php endif; ?>></div>
		</div>
	</div>
</section>

<!-- ========== MESSAGE ========== -->
<section class="message-section" id="message">
	<div class="message-section__inner">
		<div class="message-section__heading fade-in">
			<span class="message-section__heading-en">message</span>
			<span class="message-section__heading-sep">|</span>
			<span class="message-section__heading-jp">代表からの挨拶</span>
		</div>
		<div class="message-section__content fade-in">
			<div class="message-section__photo"<?php if ( $message_photo_url ) : ?> style="background-image:url('<?php echo esc_url( $message_photo_url ); ?>')"<?php endif; ?>></div>
			<div class="message-section__body">
				<div class="message-section__greeting">
					<span class="message-section__greeting-icon"></span>
					<span class="message-section__greeting-text">ごあいさつ</span>
				</div>
				<div class="message-section__quote">
					<?php if ( $message_body ) : ?>
						<?php echo wp_kses_post( wpautop( $message_body ) ); ?>
					<?php else : ?>
					<p>合同会社あんじゅは大田区内で2023年からは障害をお持ちの方向けのグループホームを、2025年からはより良い医療福祉の形を目指し訪問看護ステーションを運営しています。<br><br>理念である「関わるすべての人の笑顔の実現」に向けて、柔軟な思考を持ち、業界の垣根を越えての協働を推進しています。<br><br>医療福祉や地域社会の明日がしなやかで生き生きとしたものになることを信じて、仲間と共に挑戦を続けてまいります。</p>
					<?php endif; ?>
				</div>
				<div class="message-section__name">
					<?php echo esc_html( $company ); ?>　代表社員
					<strong><?php echo esc_html( $message_signer ?: '代表者名' ); ?></strong>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ========== ABOUT ========== -->
<section class="about-section" id="about">
	<div class="about-section__inner">
		<div class="about-section__heading fade-in">
			<span class="about-section__heading-en">about</span>
			<span class="about-section__heading-sep">|</span>
			<span class="about-section__heading-jp">会社概要</span>
		</div>
		<div class="about-section__card fade-in">
			<table class="about-section__table">
				<tbody>
					<tr><th>社名</th><td><?php echo esc_html( $company ); ?></td></tr>
					<tr><th>設立</th><td><?php echo esc_html( anju_option( 'site_established', '2022年9月' ) ); ?></td></tr>
					<tr><th>代表社員</th><td><?php echo esc_html( $message_signer ?: '吉田太樹' ); ?></td></tr>
					<tr>
						<th>事業所</th>
						<td>
							<p class="about-section__office"><strong>グループホームあんじゅ大森南</strong><br>〒143-0013 東京都大田区大森南3-29-10</p>
							<p class="about-section__office"><strong>グループホームあんじゅ大岡山①</strong><br>〒145-0063 東京都大田区南千束3-3-14 1階</p>
							<p class="about-section__office"><strong>グループホームあんじゅ大岡山②</strong><br>〒145-0063 東京都大田区南千束3-3-14 2階・3階</p>
							<p class="about-section__office"><strong>グループホームあんじゅ西蒲田</strong><br>〒144-0051 東京都大田区西蒲田6-16-5</p>
							<p class="about-section__office"><strong>訪問看護ステーションあんじゅ</strong><br>〒143-0023 東京都大田区山王3-35-7 八景坂マンション301号室</p>
						</td>
					</tr>
					<tr><th>事業内容</th><td>グループホーム事業（大田区内4ユニット、合計30居室）/ 訪問看護事業（大田区・目黒区・品川区）</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/contact-cta' ); ?>

<?php get_footer(); ?>
