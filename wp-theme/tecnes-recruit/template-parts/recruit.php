<?php
$tab1_default = "募集職種 | 建設工事スタッフ／施工管理／電気通信工事\n雇用形態 | 正社員\n応募資格 | 学歴・経験不問／高卒以上／普通自動車免許（AT限定可）\n勤務地 | 本社（大阪府東大阪市）または各現場\n勤務時間 | 8:00〜17:00（休憩60分／実働8時間）\n休日休暇 | 完全週休2日制／祝日／年末年始／夏季休暇／有給休暇\n給与 | 月給 23万円〜（経験・能力により優遇）\n昇給・賞与 | 昇給 年1回／賞与 年2回\n諸手当 | 通勤手当／資格手当／時間外手当／役職手当\n保険 | 健康保険・厚生年金・雇用保険・労災保険 完備\n選考フロー | 書類選考 → 面接（1〜2回）→ 内定";
$tab2_default = "会社名 | 株式会社TECNES\n代表者 | 代表取締役 田中 義和\n所在地 | 〒577-0063 大阪府東大阪市川俣1-6-10\n創立 | 平成18年（2006年）12月4日\n資本金 | 1,000万円\n従業員数 | 22名\n事業内容 | 電気通信工事／電気工事／土木工事／舗装工事／とび・土木工事／管工事\n建設業許可 | 国土交通大臣許可（般-6）第27222号";

$tabs = array(
    array(
        'id'    => 'tab-spec',
        'title' => tecnes_text( 'recruit_tab1_ttl', '募集要項' ),
        'rows'  => tecnes_parse_rows( tecnes_text( 'recruit_tab1_rows', $tab1_default ) ),
    ),
    array(
        'id'    => 'tab-corp',
        'title' => tecnes_text( 'recruit_tab2_ttl', '企業・募集情報' ),
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
