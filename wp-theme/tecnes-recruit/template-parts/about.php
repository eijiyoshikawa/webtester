<?php
/*
 * ABOUT セクションの数字。各カードは PHP 配列で編集できます。
 * label / target / unit / sub を変更してご利用ください。
 */
$num_cards = array(
    array( 'ico' => 'about_ico01.svg', 'label' => '創業年数',     'target' => 19,   'unit' => '年',     'sub' => '2006年（平成18年）12月 創立' ),
    array( 'ico' => 'about_ico02.svg', 'label' => '資本金',       'target' => 1000, 'unit' => '万円',   'sub' => '' ),
    array( 'ico' => 'about_ico03.svg', 'label' => '建設業種',     'target' => 6,    'unit' => '種',     'sub' => '電気通信・電気・土木・舗装・とび土木・管工事' ),
    array( 'ico' => 'about_ico04.svg', 'label' => '従業員数',     'target' => 22,   'unit' => '名',     'sub' => '' ),
    array( 'ico' => 'about_ico05.svg', 'label' => '本社所在地',   'target' => 1,    'unit' => '拠点',   'sub' => '大阪府東大阪市' ),
    array( 'ico' => 'about_ico06.svg', 'label' => '年間休日',     'target' => 105,  'unit' => '日',     'sub' => '※目安。詳細は採用情報をご確認ください' ),
    array( 'ico' => 'about_ico07.svg', 'label' => '就業時間/日',  'target' => 8,    'unit' => '時間',   'sub' => '' ),
    array( 'ico' => 'about_ico08.svg', 'label' => '許可番号',     'target' => 27222,'unit' => '号',     'sub' => '国土交通大臣許可（般-6）' ),
);
?>
<section id="about" class="about">
    <div class="l-inner aboutInner">
        <h2 class="ttl01 js-animate fadeIn01">
            <span class="ttl01En">ABOUT US</span>
            <span class="ttl01Ja">株式会社TECNESについて</span>
        </h2>

        <p class="subTtl">数字でわかる 株式会社TECNESの今</p>

        <div class="numGrid">
            <?php foreach ( $num_cards as $card ) : ?>
                <div class="numCard" data-counter-target="<?php echo esc_attr( $card['target'] ); ?>">
                    <span class="numCardIco">
                        <img src="<?php echo tecnes_img( $card['ico'] ); ?>" alt="">
                    </span>
                    <span class="numCardLabel"><?php echo esc_html( $card['label'] ); ?></span>
                    <span class="numCardValue">
                        <span class="numCardNum" data-counter-num>0</span>
                        <span class="numCardUnit"><?php echo esc_html( $card['unit'] ); ?></span>
                    </span>
                    <?php if ( ! empty( $card['sub'] ) ) : ?>
                        <span class="numCardSub"><?php echo esc_html( $card['sub'] ); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mainImg">
            <img src="<?php echo tecnes_image( 'about_main', 'about_img01@2x.jpg' ); ?>" alt="ABOUT US">
        </div>
    </div>
</section>
