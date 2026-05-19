<?php
$tab1_default = "仕事内容 | 一般電気工事、通信設備工事、電気土木工事、空調・衛生工事など幅広い工事業務。大型商業施設やマンションの新築・改修工事で、基幹設備構築から照明・コンセント・各種機械の施工を担当\n雇用形態 | 正社員\n応募資格 | 学歴・経験不問／高卒以上／普通自動車免許（AT限定可）／未経験OK\n勤務地 | 本社（大阪府東大阪市）または各現場\n勤務時間 | 8:00〜17:00（休憩60分／実働8時間）\n休日休暇 | 完全週休2日制／祝日／年末年始／夏季休暇／有給休暇\n給与 | 月給 23.5万円〜（経験・能力により優遇）\n昇給・賞与 | 昇給 年1回／賞与 年2回\n諸手当 | 通勤手当／資格手当／時間外手当／役職手当\n保険 | 健康保険・厚生年金・雇用保険・労災保険 完備\n選考フロー | 書類選考 → 面接（1〜2回）→ 内定";
$tab2_default = "仕事内容 | 電気・通信・土木・空調・衛生工事に関する営業活動。「電気をつなぎ、社会を動かす」をテーマに、お客様と現場をつなぐ役割\n雇用形態 | 正社員\n応募資格 | 学歴・経験不問／高卒以上／普通自動車免許（AT限定可）／未経験OK\n勤務地 | 大阪工事事務所（本社）：大阪府東大阪市川俣1-6-10　※マイカー通勤可能\n勤務時間 | 8:00〜17:00　※フレックスタイムや変形労働など、ライフスタイルに合わせた勤務スタイルが可能\n休日休暇 | 完全週休2日制／祝日／年末年始／夏季休暇／有給休暇\n給与 | 月給 23.5万円〜（経験・能力により優遇）\n昇給・賞与 | 昇給 年1回／賞与 年2回\n諸手当 | 通勤手当／資格手当／時間外手当／役職手当／インセンティブ制度あり\n保険 | 健康保険・厚生年金・雇用保険・労災保険 完備\n選考フロー | 書類選考 → 面接（1〜2回）→ 内定";

$tabs = array(
    array(
        'id'    => 'tab-tech',
        'title' => tecnes_text( 'recruit_tab1_ttl', '工事技術職' ),
        'rows'  => tecnes_parse_rows( tecnes_text( 'recruit_tab1_rows', $tab1_default ) ),
    ),
    array(
        'id'    => 'tab-sales',
        'title' => tecnes_text( 'recruit_tab2_ttl', '営業職' ),
        'rows'  => tecnes_parse_rows( tecnes_text( 'recruit_tab2_rows', $tab2_default ) ),
    ),
);
?>
<section id="recruit" class="recruit">
    <div class="l-inner recruitInner">
        <h2 class="ttl01 js-animate fadeIn01">
            <span class="ttl01En"><?php echo esc_html( tecnes_text( 'recruit_ttl_en', 'RECRUIT' ) ); ?></span>
            <span class="ttl01Ja"><?php echo esc_html( tecnes_text( 'recruit_ttl_ja', '募集要項' ) ); ?></span>
        </h2>

        <div class="recruitTabs" role="tablist">
            <?php foreach ( $tabs as $i => $tab ) : ?>
                <button
                    type="button"
                    class="recruitTab<?php echo $i === 0 ? ' is-active' : ''; ?>"
                    data-tab-target="<?php echo esc_attr( $tab['id'] ); ?>"
                    role="tab"
                    aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                ><?php echo esc_html( $tab['title'] ); ?></button>
            <?php endforeach; ?>
        </div>

        <div class="recruitPanels">
            <?php foreach ( $tabs as $i => $tab ) : ?>
                <div
                    id="<?php echo esc_attr( $tab['id'] ); ?>"
                    class="recruitPanel<?php echo $i === 0 ? ' is-active' : ''; ?>"
                    role="tabpanel"
                >
                    <dl class="recruitList">
                        <?php foreach ( $tab['rows'] as $row ) : ?>
                            <div class="recruitRow">
                                <dt><?php echo esc_html( $row[0] ); ?></dt>
                                <dd><?php echo esc_html( $row[1] ); ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
