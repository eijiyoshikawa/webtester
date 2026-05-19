<?php
$ac1_default = "社会保険 | 健康保険・厚生年金・雇用保険・労災保険 完備\n休日休暇 | 完全週休2日制／祝日／年末年始／夏季休暇／有給休暇／慶弔休暇\n各種手当 | 通勤手当／資格手当／時間外手当／役職手当\n退職金制度 | あり\n健康診断 | 年1回（35歳以上は人間ドック）\n研修制度 | 入社時研修／OJT／外部研修参加支援";
$ac2_default = "資格取得支援 | 受験費用・講習費用を会社が負担\n報奨金制度 | 指定資格取得時に報奨金を支給\n対象資格例 | 電気工事士／施工管理技士／玉掛け／高所作業車運転 ほか";

$accordion_items = array(
    array(
        'title' => tecnes_text( 'env_ac1_ttl', '福利厚生' ),
        'rows'  => tecnes_parse_rows( tecnes_text( 'env_ac1_rows', $ac1_default ) ),
    ),
    array(
        'title' => tecnes_text( 'env_ac2_ttl', '資格取得等褒章制度' ),
        'rows'  => tecnes_parse_rows( tecnes_text( 'env_ac2_rows', $ac2_default ) ),
    ),
);
?>
<section id="environment" class="environment">
    <div class="l-inner environmentInner">
        <h2 class="ttl01 js-animate fadeIn01">
            <span class="ttl01En"><?php echo esc_html( tecnes_text( 'env_ttl_en', 'ENVIRONMENT' ) ); ?></span>
            <span class="ttl01Ja"><?php echo esc_html( tecnes_text( 'env_ttl_ja', '働く環境を知る' ) ); ?></span>
        </h2>
        <p class="leadTxt">
            <?php echo esc_html( tecnes_text( 'env_lead', 'みなさんが安心して働くことのできる環境、会社とともに成長できる環境を整えております。' ) ); ?>
        </p>
        <div class="envAccordion">
            <?php foreach ( $accordion_items as $i => $item ) : ?>
                <details class="envAcItem">
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
