<?php
$num_cards = array(
    array( 'label' => '創業年数',     'target' => 30,   'unit' => '年',     'sub' => '' ),
    array( 'label' => '建設業種',     'target' => 6,    'unit' => '業種',   'sub' => '電気通信・電気・土木・舗装・とび土木・管工事' ),
    array( 'label' => '拠点数',       'target' => 3,    'unit' => '拠点',   'sub' => '' ),
    array( 'label' => '従業員数',     'target' => 22,   'unit' => '人',     'sub' => '' ),
    array( 'label' => '資本金',       'target' => 1000, 'unit' => '万円',   'sub' => '' ),
    array( 'label' => '年間休日',     'target' => 120,  'unit' => '日',     'sub' => '' ),
    array( 'label' => '就業時間/日',  'target' => 8,    'unit' => '時間',   'sub' => '' ),
    array( 'label' => '平均勤続年数', 'target' => 10,   'unit' => '年',     'sub' => '' ),
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
