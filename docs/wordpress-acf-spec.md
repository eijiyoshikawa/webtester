# WordPress 移行準備：カスタムフィールド設計書

> 静的 HTML から WordPress 移行時に、管理画面から **すべての画像・PDF・テキスト** を非エンジニアでも差し替えられるようにするためのカスタムフィールド設計仕様。
>
> **📌 現在の実装状況：** `wp-theme/anju/` は **Carbon Fields（完全無料）** ベースで実装されています。フィールド定義は `wp-theme/anju/inc/carbon-fields-setup.php` 参照。
>
> ACF Pro 版（年額 $49〜）も `wp-theme/anju-acf-backup/` に保管しています。下記設計はどちらのプラグインでも適用可能です。

## 前提

- **必須プラグイン：** ACF Pro（推奨）
  - Repeater / Gallery / Flexible Content フィールドが必要
  - ライセンス：年額 USD $49〜（複数サイト可のプランあり）
- **テーマ：** カスタムテーマ または 既存テーマの child theme
- **対応ページ：** WP 固定ページとして以下を作成
  - `home`（トップ）
  - `houkan`（訪問看護）
  - `grouphome`（グループホーム）
  - `news`（お知らせ一覧）／ `blog`（ブログ一覧）— カスタム投稿タイプ推奨
  - `contact`（問い合わせ）／ `privacy-policy`（プライバシー）

---

## フィールドグループ一覧（全体像）

| # | グループ名 | 表示条件 | 内容 |
|---|------------|----------|------|
| 1 | トップ - ヒーロースライダー | 固定ページ「Home」 | スライド画像群 |
| 2 | トップ - philosophy セクション | 固定ページ「Home」 | 画像3枚＋テキスト |
| 3 | トップ - message セクション | 固定ページ「Home」 | 代表写真＋本文 |
| 4 | トップ - サービスロゴ | 固定ページ「Home」 | グループホーム/訪問看護ロゴ |
| 5 | 訪問看護 - メイン写真 | 固定ページ「Houkan」 | ページトップ画像 |
| 6 | 訪問看護 - 料金/サービスPDF | 固定ページ「Houkan」 | PDFカード |
| 7 | 訪問看護 - ギャラリー | 固定ページ「Houkan」 | スライダー画像群 |
| 8 | グループホーム - メイン写真 | 固定ページ「Grouphome」 | ページトップ画像 |
| 9 | グループホーム - ホーム一覧 | 固定ページ「Grouphome」 | 拠点情報＋画像 |
| 10 | グループホーム - ギャラリー | 固定ページ「Grouphome」 | スライダー画像群 |
| 11 | お知らせ（News） | カスタム投稿タイプ | タイトル・日付・本文 |
| 12 | ブログ（Blog） | カスタム投稿タイプ | アイキャッチ・本文 |
| 13 | サイト共通設定 | テーマオプション | ロゴ・SNS・連絡先 |

---

## 1. トップ - ヒーロースライダー

**表示条件：** Page = Home（slug: `/`）
**現状：** `index.html:58-65`（6枚）

| フィールド | キー | タイプ | 備考 |
|------------|------|--------|------|
| ヒーロースライダー画像 | `home_hero_slides` | Gallery | 推奨5〜8枚、推奨1920×1080以上 |
| メインキャッチコピー | `home_hero_title` | Text | 例:「ひとつでも多くの笑顔に出会うために」 |
| サブキャッチコピー | `home_hero_subtitle` | Textarea | 例:「確かな支援で今日を支え、明日の笑顔をつなぐ」 |

**実装イメージ：**
```php
<?php $slides = get_field('home_hero_slides'); ?>
<section class="hero" id="home">
  <div class="hero__slideshow">
    <?php foreach ($slides as $i => $img): ?>
      <div class="hero__slide<?= $i === 0 ? ' hero__slide--active' : '' ?>" style="background-image:url('<?= esc_url($img['url']) ?>')"></div>
    <?php endforeach; ?>
  </div>
  <div class="hero__overlay"></div>
  <div class="hero__content">
    <h1 class="hero__title fade-in"><?= esc_html(get_field('home_hero_title')) ?></h1>
    <p class="hero__subtitle fade-in fade-in--delay1"><?= wp_kses_post(get_field('home_hero_subtitle')) ?></p>
  </div>
</section>
```

---

## 2. トップ - philosophy セクション

**現状：** `index.html:77-101` + `assets/css/style.css:721-729`（背景画像）

| フィールド | キー | タイプ |
|------------|------|--------|
| philosophy 大画像（上） | `home_philosophy_image_large` | Image |
| philosophy 小画像（下左） | `home_philosophy_image_left` | Image |
| philosophy 小画像（下右） | `home_philosophy_image_right` | Image |
| リード文 | `home_philosophy_lead` | Textarea |
| 本文 | `home_philosophy_body` | WYSIWYG |

---

## 3. トップ - message（代表挨拶）セクション

**現状：** `index.html:170-198` + `assets/css/style.css:1472`（背景画像）

| フィールド | キー | タイプ |
|------------|------|--------|
| 代表写真 | `home_message_photo` | Image |
| あいさつ本文 | `home_message_body` | WYSIWYG |
| 代表者名 | `home_message_signer` | Text |

---

## 4. トップ - サービスロゴ（事業紹介）

**現状：** `assets/css/style.css:1081,1085`（背景画像でロゴ表示）

| フィールド | キー | タイプ | 備考 |
|------------|------|--------|------|
| グループホームロゴ | `home_logo_grouphome` | Image | 推奨：透過PNG |
| 訪問看護ロゴ | `home_logo_houkan` | Image | 推奨：透過PNG |

CSS で `background-image` を直接読み込む場合は、テーマ側で `wp_add_inline_style` でロゴ URL を注入する。

---

## 5. 訪問看護 - メイン写真

**現状：** `service/houkan/index.html:70`

| フィールド | キー | タイプ |
|------------|------|--------|
| メイン写真 | `houkan_hero_image` | Image |

---

## 6. 訪問看護 - 料金/サービスPDF

**現状：** `service/houkan/index.html:144-172`

| フィールド | キー | タイプ |
|------------|------|--------|
| 料金表PDF | `houkan_price_pdf` | File |
| 料金表タイトル | `houkan_price_title` | Text |
| 料金表説明 | `houkan_price_desc` | Textarea |
| サービス詳細PDF | `houkan_service_pdf` | File |
| サービス詳細タイトル | `houkan_service_title` | Text |
| サービス詳細説明 | `houkan_service_desc` | Textarea |

**実装イメージ（PDFカード）：**
```php
$cards = [
  ['pdf' => get_field('houkan_price_pdf'),   'title' => get_field('houkan_price_title'),   'desc' => get_field('houkan_price_desc')],
  ['pdf' => get_field('houkan_service_pdf'), 'title' => get_field('houkan_service_title'), 'desc' => get_field('houkan_service_desc')],
];
foreach ($cards as $c):
  if (empty($c['pdf'])) continue; ?>
  <a href="<?= esc_url($c['pdf']['url']) ?>" class="svc-fees__card" target="_blank" rel="noopener">
    <!-- アイコンSVG -->
    <div class="svc-fees__card-body">
      <span class="svc-fees__card-label">PDF</span>
      <h3 class="svc-fees__card-title"><?= esc_html($c['title']) ?></h3>
      <p class="svc-fees__card-desc"><?= esc_html($c['desc']) ?></p>
    </div>
  </a>
<?php endforeach;
```

---

## 7. 訪問看護 - ギャラリー

**現状：** `service/houkan/index.html:187-200`

| フィールド | キー | タイプ |
|------------|------|--------|
| ギャラリー画像 | `houkan_gallery` | Gallery |

**実装イメージ：**
```php
$gallery = get_field('houkan_gallery');
if ($gallery): ?>
  <div class="svc-gallery__track">
    <?php for ($i = 0; $i < 2; $i++): // 無限ループ用に2回繰り返し
      foreach ($gallery as $img): ?>
        <div class="svc-gallery__item" style="background-image:url('<?= esc_url($img['url']) ?>')"></div>
      <?php endforeach;
    endfor; ?>
  </div>
<?php endif;
```

---

## 8. グループホーム - メイン写真

**現状：** `service/grouphome/index.html:70`

| フィールド | キー | タイプ |
|------------|------|--------|
| メイン写真 | `grouphome_hero_image` | Image |

---

## 9. グループホーム - ホーム一覧（拠点カード）

**現状：** `service/grouphome/index.html:104-152`（4拠点）

| フィールド | キー | タイプ |
|------------|------|--------|
| ホーム一覧 | `grouphome_homes` | Repeater |

**Repeater サブフィールド：**

| サブキー | タイプ | 備考 |
|----------|--------|------|
| `name` | Text | 拠点名 |
| `address` | Text | 住所 |
| `image_left` | Image | 左側写真 |
| `image_right` | Image | 右側写真 |

**実装イメージ：**
```php
if (have_rows('grouphome_homes')):
  while (have_rows('grouphome_homes')): the_row();
    $left  = get_sub_field('image_left');
    $right = get_sub_field('image_right'); ?>
    <article class="svc-home-card">
      <div class="svc-home-card__images">
        <div class="svc-home-card__image" style="background-image:url('<?= esc_url($left['url']) ?>')"></div>
        <div class="svc-home-card__image" style="background-image:url('<?= esc_url($right['url']) ?>')"></div>
      </div>
      <div class="svc-home-card__body">
        <h4 class="svc-home-card__name"><?= esc_html(get_sub_field('name')) ?></h4>
        <p class="svc-home-card__addr"><?= esc_html(get_sub_field('address')) ?></p>
      </div>
    </article>
  <?php endwhile;
endif;
```

---

## 10. グループホーム - ギャラリー

**現状：** `service/grouphome/index.html:170-181`

| フィールド | キー | タイプ |
|------------|------|--------|
| ギャラリー画像 | `grouphome_gallery` | Gallery |

実装は §7 と同形式。

---

## 11. お知らせ（News）

**現状：** `index.html:107-122` に2件ハードコード

**カスタム投稿タイプ：** `news`
WP 標準投稿として運用、または以下のフィールド定義：

| フィールド | キー | タイプ |
|------------|------|--------|
| 公開日 | `post_date`（WP標準） | Date |
| タイトル | `post_title`（WP標準） | Text |
| 本文 | `post_content`（WP標準） | WYSIWYG |
| アイキャッチ | `_thumbnail_id`（WP標準） | Image |
| 外部リンク | `news_external_url` | URL（任意） |

---

## 12. ブログ（Blog）

WP 標準の「投稿（post）」をそのまま利用可能。または `blog` カスタム投稿タイプを作成。

---

## 13. サイト共通設定（テーマオプション）

ACF の Options Page を作成し、サイト全体で使う情報を一元管理：

| フィールド | キー | タイプ |
|------------|------|--------|
| 会社ロゴ（ヘッダー/フッター） | `site_logo` | Image |
| 会社名（日本語） | `site_company_jp` | Text |
| 会社名（英語） | `site_company_en` | Text |
| 住所 | `site_address` | Textarea |
| 電話番号 | `site_tel` | Text |
| 代表メール | `site_email` | Email |
| Instagram URL | `site_sns_instagram` | URL |
| Facebook URL | `site_sns_facebook` | URL |
| お問い合わせフォームURL | `site_contact_url` | URL |
| フッター コピーライト | `site_copyright` | Text |

これにより、SNS や住所変更も管理画面から一元更新可能。

---

## ACF 無料版で進める場合の代替案

| 機能 | Pro | 無料版での代替 |
|------|-----|----------------|
| File（PDF）フィールド | ○ | ○（無料版で対応） |
| Image フィールド | ○ | ○（無料版で対応） |
| Gallery フィールド | ○ | × → Gutenberg「Gallery ブロック」を本文に配置し `the_content()` で取得 |
| Repeater フィールド | ○ | × → 「Carbon Fields」「Meta Box Lite」等の代替プラグイン |
| Options Page | ○ | × → 「ACF Options Page Adder」等の補助プラグイン |

**推奨：** 全画像差し替え対応のため、**ACF Pro 必須**。

---

## ACF フィールドグループ JSON ひな形

WordPress 移行時、ACF 管理画面から「ツール → フィールドグループをインポート」で読み込めるよう、`docs/acf-fields.json` を別途作成可能（要望時にお知らせください）。

---

## 移行時の注意事項

1. **画像/PDFの引き継ぎ：** `assets/images/` `assets/docs/` を WP メディアライブラリへ一括インポート
   - プラグイン例：「Add From Server」「Media Sync」
2. **URL構造：** `/service/houkan/` 等のパスは固定ページ permalink で再現可能
3. **CSS：** 既存 `assets/css/style.css` をテーマの `style.css` または `assets/` 配下に移行
4. **キャッシュ：** WP Rocket / W3 Total Cache 等を導入推奨
5. **画像最適化：** ShortPixel / EWWW Image Optimizer 等でWebP化

---

## 関連ファイル参照

| 項目 | ファイル | 行 |
|------|----------|-----|
| ヒーロースライダー | `index.html` | 58-65 |
| philosophy | `index.html` | 77-101 |
| message | `index.html` | 170-198 |
| サービスロゴ CSS | `assets/css/style.css` | 1081, 1085 |
| 訪問看護 メイン写真 | `service/houkan/index.html` | 70 |
| 訪問看護 PDF | `service/houkan/index.html` | 144-172 |
| 訪問看護 ギャラリー | `service/houkan/index.html` | 187-200 |
| グループホーム メイン写真 | `service/grouphome/index.html` | 70 |
| グループホーム ホーム一覧 | `service/grouphome/index.html` | 104-152 |
| グループホーム ギャラリー | `service/grouphome/index.html` | 170-181 |
