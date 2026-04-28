<?php
/**
 * Template Name: プライバシーポリシー
 *
 * @package Anju
 */
get_header();
?>

<section class="svc-hero fade-in">
	<div class="svc-hero__bar svc-hero__bar--left"></div>
	<div class="svc-hero__bar svc-hero__bar--right"></div>
	<div class="svc-hero__inner">
		<h1 class="svc-hero__title">privacy</h1>
		<div class="svc-hero__logo">
			<span class="svc-hero__logo-text">anju</span>
		</div>
	</div>
</section>

<section class="privacy-page">
	<div class="container">
		<div class="privacy-page__header fade-in">
			<h1 class="privacy-page__title">privacy policy</h1>
			<p class="privacy-page__subtitle">プライバシーポリシー</p>
		</div>

		<div class="privacy-page__body fade-in">
			<?php if ( get_the_content() ) : ?>
				<?php the_content(); ?>
			<?php else : ?>
				<p class="privacy-page__intro">合同会社あんじゅ（以下「当社」といいます。）は、利用者さまの個人情報の重要性を認識し、その保護の徹底を図るため、以下のとおり個人情報保護方針を定め、これを遵守いたします。</p>

				<article class="privacy-page__section">
					<h2 class="privacy-page__heading">1. 個人情報の取得について</h2>
					<p>当社は、サービスの提供および当社業務の遂行に必要な範囲内で、適法かつ公正な手段により個人情報を取得します。</p>
				</article>

				<article class="privacy-page__section">
					<h2 class="privacy-page__heading">2. 個人情報の利用目的</h2>
					<p>当社は、取得した個人情報を以下の目的のために利用します。</p>
					<ul class="privacy-page__list">
						<li>グループホーム事業および訪問看護事業に係るサービス提供のため</li>
						<li>お問い合わせへのご回答のため</li>
						<li>関係機関との連携・連絡のため</li>
						<li>当社サービスに関するご案内のため</li>
						<li>その他、上記の目的に付随する業務の遂行のため</li>
					</ul>
				</article>

				<article class="privacy-page__section">
					<h2 class="privacy-page__heading">3. 個人情報の第三者提供</h2>
					<p>当社は、法令に定める場合を除き、ご本人の同意を得ることなく、取得した個人情報を第三者に提供することはありません。</p>
				</article>

				<article class="privacy-page__section">
					<h2 class="privacy-page__heading">4. 個人情報の安全管理</h2>
					<p>当社は、取得した個人情報の漏えい、滅失またはき損の防止その他個人情報の安全管理のために、必要かつ適切な措置を講じます。</p>
				</article>

				<article class="privacy-page__section">
					<h2 class="privacy-page__heading">5. 個人情報の開示・訂正・削除等</h2>
					<p>ご本人から個人情報の開示・訂正・削除等のご要望があった場合には、本人確認を行ったうえで、合理的な範囲で速やかに対応いたします。</p>
				</article>

				<article class="privacy-page__section">
					<h2 class="privacy-page__heading">6. 法令・規範の遵守と見直し</h2>
					<p>当社は、保有する個人情報に関して適用される日本の法令、その他規範を遵守するとともに、本ポリシーの内容を適宜見直し、その改善に努めます。</p>
				</article>

				<article class="privacy-page__section">
					<h2 class="privacy-page__heading">7. お問い合わせ窓口</h2>
					<p>個人情報の取扱いに関するお問い合わせは、以下の窓口までご連絡ください。</p>
					<p class="privacy-page__contact">
						<?php echo esc_html( anju_option( 'site_company_jp', '合同会社あんじゅ' ) ); ?><br>
						お問い合わせフォーム: <a href="<?php echo esc_url( anju_option( 'site_contact_url', home_url( '/contact/' ) ) ); ?>">こちら</a>
					</p>
				</article>

				<p class="privacy-page__date">制定日：2024年1月1日</p>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
