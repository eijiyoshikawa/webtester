<?php
/*
 * SP 専用 LINE 応募 固定 CTA バナー（画面下部固定）。
 * - スマホ表示時のみ常駐し、応募までの導線を最短化
 * - LINE 緑のブランドカラー、白アイコン＋矢印アニメで視認性とタップ誘発を両立
 */
$line_url = tecnes_line_url();
?>
<a href="<?php echo $line_url; ?>" class="spLineCta" target="_blank" rel="noopener noreferrer" aria-label="LINE で応募する">
    <span class="spLineCtaIco" aria-hidden="true">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" focusable="false">
            <path fill="currentColor" d="M16 3.2C8.1 3.2 1.8 8.4 1.8 14.8c0 5.7 5 10.5 11.8 11.4.5.1 1.1.3 1.3.7.2.4.1 1-.1 1.4 0 0-.2 1-.2 1.2-.1.4-.3 1.4 1.2.8 1.5-.7 8.3-4.9 11.3-8.4h0c2.1-2.3 3.1-4.7 3.1-7.1C30.2 8.4 23.9 3.2 16 3.2zM10.4 18.4H7.7c-.4 0-.7-.3-.7-.7v-5.5c0-.4.3-.7.7-.7s.7.3.7.7v4.8h2c.4 0 .7.3.7.7s-.3.7-.7.7zm2.7-.7c0 .4-.3.7-.7.7s-.7-.3-.7-.7v-5.5c0-.4.3-.7.7-.7s.7.3.7.7v5.5zm6.5 0c0 .3-.2.6-.5.7-.1 0-.2.1-.2.1-.2 0-.4-.1-.6-.3l-2.8-3.8v3.4c0 .4-.3.7-.7.7s-.7-.3-.7-.7v-5.5c0-.3.2-.6.5-.7.1 0 .2-.1.2-.1.2 0 .4.1.5.3l2.8 3.8v-3.3c0-.4.3-.7.7-.7s.7.3.7.7v5.4zm4.4-3.5c.4 0 .7.3.7.7s-.3.7-.7.7h-2v1.3h2c.4 0 .7.3.7.7s-.3.7-.7.7h-2.7c-.4 0-.7-.3-.7-.7v-5.5c0-.4.3-.7.7-.7H24c.4 0 .7.3.7.7s-.3.7-.7.7h-2v1.3h2z"/>
        </svg>
    </span>
    <span class="spLineCtaTxt">
        <span class="spLineCtaTxtMain">LINEで かんたん応募</span>
        <span class="spLineCtaTxtSub">タップで友だち追加 → エントリー</span>
    </span>
    <span class="spLineCtaArrow" aria-hidden="true">›</span>
</a>
