<?php
$tabs = array(
    array(
        'id'    => 'tab-spec',
        'title' => '募集要項',
        'rows'  => array(
            array( '募集職種',     '建設工事スタッフ／施工管理／電気通信工事' ),
            array( '雇用形態',     '正社員' ),
            array( '応募資格',     '学歴・経験不問／高卒以上／普通自動車免許（AT限定可）' ),
            array( '勤務地',       '本社（大阪府東大阪市）または各現場' ),
            array( '勤務時間',     '8:00〜17:00（休憩60分／実働8時間）' ),
            array( '休日休暇',     '完全週休2日制／祝日／年末年始／夏季休暇／有給休暇' ),
            array( '給与',         '月給 23万円〜（経験・能力により優遇）' ),
            array( '昇給・賞与',   '昇給 年1回／賞与 年2回' ),
            array( '諸手当',       '通勤手当／資格手当／時間外手当／役職手当' ),
            array( '保険',         '健康保険・厚生年金・雇用保険・労災保険 完備' ),
            array( '選考フロー',   '書類選考 → 面接（1〜2回）→ 内定' ),
        ),
    ),
    array(
        'id'    => 'tab-corp',
        'title' => '企業・募集情報',
        'rows'  => array(
            array( '会社名',         '株式会社TECNES' ),
            array( '代表者',         '代表取締役 田中 義和' ),
            array( '所在地',         '〒577-0063 大阪府東大阪市川俣1-6-10' ),
            array( '創立',           '平成18年（2006年）12月4日' ),
            array( '資本金',         '1,000万円' ),
            array( '従業員数',       '22名' ),
            array( '事業内容',       '電気通信工事／電気工事／土木工事／舗装工事／とび・土木工事／管工事' ),
            array( '建設業許可',     '国土交通大臣許可（般-6）第27222号' ),
        ),
    ),
);
?>
<section id="recruit" class="recruit">
    <div class="l-inner recruitInner">
        <h2 class="ttl01 js-animate fadeIn01">
            <span class="ttl01En">RECRUIT</span>
            <span class="ttl01Ja">募集要項</span>
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
