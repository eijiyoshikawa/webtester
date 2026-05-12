<?php
$cards_default = "創業年数|30|年|\n建設業種|6|業種|電気通信・電気・土木・舗装・とび土木・管工事\n拠点数|3|拠点|\n従業員数|22|人|\n資本金|1000|万円|\n年間休日|120|日|\n就業時間/日|8|時間|\n平均勤続年数|10|年|";
$num_cards = tecnes_parse_rows( tecnes_text( 'about_cards', $cards_default ), 4 );
?>
<section id="about" class="about">
    <div class="l-inner aboutInner">
        <h2 class="ttl01 js-animate fadeIn01">
            <span class="ttl01En"><?php echo esc_html( tecnes_text( 'about_ttl_en', 'ABOUT US' ) ); ?></span>
            <span class="ttl01Ja"><?php echo esc_html( tecnes_text( 'about_ttl_ja', '株式会社TECNESについて' ) ); ?></span>
        </h2>

        <p class="subTtl"><?php echo esc_html( tecnes_text( 'about_sub', '数字でわかる 株式会社TECNESの今' ) ); ?></p>

        <div class="numGrid">
            <?php foreach ( $num_cards as $card ) :
                list( $label, $target, $unit, $sub ) = $card;
            ?>
                <div class="numCard" data-counter-target="<?php echo esc_attr( $target ); ?>">
                    <span class="numCardLabel"><?php echo esc_html( $label ); ?></span>
                    <span class="numCardValue">
                        <span class="numCardNum" data-counter-num>0</span>
                        <span class="numCardUnit"><?php echo esc_html( $unit ); ?></span>
                    </span>
                    <?php if ( $sub !== '' ) : ?>
                        <span class="numCardSub"><?php echo esc_html( $sub ); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mainImg">
            <img src="<?php echo tecnes_image( 'about_main', 'about_img01@2x.jpg' ); ?>" alt="ABOUT US">
        </div>
    </div>
</section>
