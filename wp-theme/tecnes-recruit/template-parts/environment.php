<?php
$accordion_items = array(
    array(
        'title' => '福利厚生',
        'rows'  => array(
            array( '社会保険',     '健康保険・厚生年金・雇用保険・労災保険 完備' ),
            array( '休日休暇',     '完全週休2日制／祝日／年末年始／夏季休暇／有給休暇／慶弔休暇' ),
            array( '各種手当',     '通勤手当／資格手当／時間外手当／役職手当' ),
            array( '退職金制度',   'あり' ),
            array( '健康診断',     '年1回（35歳以上は人間ドック）' ),
            array( '研修制度',     '入社時研修／OJT／外部研修参加支援' ),
        ),
    ),
    array(
        'title' => '資格取得等褒章制度',
        'rows'  => array(
            array( '資格取得支援', '受験費用・講習費用を会社が負担' ),
            array( '報奨金制度',   '指定資格取得時に報奨金を支給' ),
            array( '対象資格例',   '電気工事士／施工管理技士／玉掛け／高所作業車運転 ほか' ),
        ),
    ),
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
        <div class="envAccordion">
            <?php foreach ( $accordion_items as $i => $item ) : ?>
                <details class="envAcItem"<?php echo $i === 0 ? ' open' : ''; ?>>
                    <summary class="envAcSummary">
                        <span class="envAcTitle"><?php echo esc_html( $item['title'] ); ?></span>
                        <span class="envAcIcon" aria-hidden="true"></span>
                    </summary>
                    <div class="envAcBody">
                        <dl class="envAcList">
                            <?php foreach ( $item['rows'] as $row ) : ?>
                                <div class="envAcRow">
                                    <dt><?php echo esc_html( $row[0] ); ?></dt>
                                    <dd><?php echo esc_html( $row[1] ); ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
