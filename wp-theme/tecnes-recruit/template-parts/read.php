<?php
$ttl_default  = "私たちは社会と産業の\n未来のために挑戦し続けます";
$body_default = "株式会社TECNESはただ商品を売るだけではありません。\n付加価値を加え、お客さまの課題解決に寄り添いながら\n社会インフラ整備と産業界の成長に貢献する商社です。\n\n私たちの仕事は、お客さま一人ひとりの要望に応え\n信頼を創り出すこと。\n\n勇気がいる最初の一歩も、本気で向き合う。\nどんな難題があっても、創意と工夫で乗り越えていく。\nそんな想いは、きっと誰かの未来をともす光になるはずです。\nこれが私たちの企業理念である「お役立ち」です。";

$ttl_html = nl2br( esc_html( tecnes_text( 'read_ttl', $ttl_default ) ) );

$body_paragraphs = preg_split( "/(\r\n|\n){2,}/", tecnes_text( 'read_body', $body_default ) );
?>
<section id="read" class="read">
    <div class="l-inner readInner">
        <div class="leftBox">
            <h2 class="ttl js-animate fadeIn01">
                <?php echo $ttl_html; ?>
            </h2>
            <?php foreach ( $body_paragraphs as $p ) :
                $p = trim( $p );
                if ( $p === '' ) continue;
            ?>
                <p class="txt js-animate fadeIn01"><?php echo nl2br( esc_html( $p ) ); ?></p>
            <?php endforeach; ?>
        </div>
        <div class="rightBox js-scroll01">
            <p class="imageWrap">
                <span class="image01">
                    <picture>
                        <source srcset="<?php echo tecnes_image( 'read_main', 'read_img01@2x.jpg' ); ?>">
                        <img src="<?php echo tecnes_image( 'read_main', 'read_img01@2x.jpg' ); ?>" alt="">
                    </picture>
                </span>
                <span class="image02">
                    <picture>
                        <source srcset="<?php echo tecnes_image( 'read_main', 'read_img01@2x.jpg' ); ?>">
                        <img src="<?php echo tecnes_image( 'read_main', 'read_img01@2x.jpg' ); ?>" alt="">
                    </picture>
                </span>
            </p>
        </div>
    </div>
</section>
