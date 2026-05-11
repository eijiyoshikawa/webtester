<?php
$cards = array(
    array( 'slot' => 'env_1', 'fallback' => 'environment_img01@2x.jpg', 'label' => '福利厚生' ),
    array( 'slot' => 'env_2', 'fallback' => 'environment_img02@2x.jpg', 'label' => '教育制度' ),
    array( 'slot' => 'env_3', 'fallback' => 'environment_img03@2x.jpg', 'label' => 'オフィス紹介' ),
    array( 'slot' => 'env_4', 'fallback' => 'environment_img04@2x.jpg', 'label' => '社員座談会 パパ・ママトーク' ),
    array( 'slot' => 'env_5', 'fallback' => 'environment_img05@2x.jpg', 'label' => '社員座談会 社風トーク' ),
    array( 'slot' => 'env_6', 'fallback' => 'environment_img06@2x.jpg', 'label' => '先輩たちの声' ),
);
?>
<section id="environment" class="environment">
    <div class="l-inner environmentInner">
        <h2 class="ttl01 js-animate fadeIn01">
            <span class="ttl01En">ENVIRONMENT</span>
            <span class="ttl01Ja">働く環境を知る</span>
        </h2>
        <p class="leadTxt">
            みなさんが安心して働くことのできる環境、会社とともに成長できる環境を整えております。
        </p>
        <div class="grid">
            <?php foreach ( $cards as $card ) : ?>
                <div class="card">
                    <div class="cardImg">
                        <img src="<?php echo tecnes_image( $card['slot'], $card['fallback'] ); ?>" alt="<?php echo esc_attr( $card['label'] ); ?>">
                    </div>
                    <p class="cardLabel"><?php echo esc_html( $card['label'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
