# WordPress 移行準備：ACF カスタムフィールド設計書

> 静的 HTML から WordPress 移行時に、管理画面から「PDF差し替え」「ギャラリー画像差し替え」を非エンジニアでも行えるようにするための ACF（Advanced Custom Fields）設計仕様。

## 前提

- **必須プラグイン：** ACF Pro（推奨）
  - 理由：Repeater / Gallery / Flexible Content フィールドが必要
  - ライセンス：年額 USD $49〜（複数サイト可のプランあり）
  - 代替：ACF 無料版 + Gutenberg ネイティブ Gallery ブロック（後述）
- **テーマ：** カスタムテーマ or 既存テーマの child theme
- **対応ページ：** 固定ページ（page）として `訪問看護` / `グループホーム` を作成

---

## フィールドグループ一覧

| # | グループ名 | 表示条件 | 用途 |
|---|------------|----------|------|
| 1 | 訪問看護 - 料金/サービスPDF | 固定ページ「訪問看護」 | PDFカード差し替え |
| 2 | 訪問看護 - ギャラリー | 固定ページ「訪問看護」 | スライダー画像管理 |
| 3 | グループホーム - ギャラリー | 固定ページ「グループホーム」 | スライダー画像管理 |
| 4 | グループホーム - ホーム一覧 | 固定ページ「グループホーム」 | 各拠点情報＋画像 |

---

## 1. 訪問看護 - 料金/サービスPDF

**表示条件：** Page = `訪問看護`（slug: `houkan` 等）

| フィールド名 | キー | タイプ | 必須 | 備考 |
|--------------|------|--------|------|------|
| 料金表PDF | `houkan_price_pdf` | File | ○ | Return Format: Array |
| 料金表タイトル | `houkan_price_title` | Text | ○ | 既定値: `訪問看護 料金表` |
| 料金表説明 | `houkan_price_desc` | Textarea | - | 既定値: `基本料金・加算項目をまとめた料金一覧です。` |
| サービス詳細PDF | `houkan_service_pdf` | File | ○ | Return Format: Array |
| サービス詳細タイトル | `houkan_service_title` | Text | ○ | 既定値: `サービス詳細` |
| サービス詳細説明 | `houkan_service_desc` | Textarea | - | 既定値: `提供サービスの内容・対象・利用方法をご案内します。` |

**現状HTML（参考）：** `service/houkan/index.html:144-172`

**テンプレート実装例（page-houkan.php）：**
```php
<section class="svc-fees">
  <div class="container">
    <div class="svc-fees__header">
      <h2 class="svc-fees__title">price list</h2>
      <p class="svc-fees__subtitle">料金表・サービス詳細</p>
    </div>
    <p class="svc-fees__lead">訪問看護ステーションあんじゅの料金表およびサービス詳細は、以下のPDFよりご覧いただけます。</p>
    <div class="svc-fees__cards">
      <?php
      $cards = [
        ['pdf' => get_field('houkan_price_pdf'),   'title' => get_field('houkan_price_title'),   'desc' => get_field('houkan_price_desc')],
        ['pdf' => get_field('houkan_service_pdf'), 'title' => get_field('houkan_service_title'), 'desc' => get_field('houkan_service_desc')],
      ];
      foreach ($cards as $c):
        if (empty($c['pdf'])) continue; ?>
        <a href="<?= esc_url($c['pdf']['url']) ?>" class="svc-fees__card" target="_blank" rel="noopener">
          <div class="svc-fees__card-icon" aria-hidden="true">
            <!-- SVG icon (現状HTMLと同じ) -->
          </div>
          <div class="svc-fees__card-body">
            <span class="svc-fees__card-label">PDF</span>
            <h3 class="svc-fees__card-title"><?= esc_html($c['title']) ?></h3>
            <p class="svc-fees__card-desc"><?= esc_html($c['desc']) ?></p>
          </div>
          <span class="svc-fees__card-arrow" aria-hidden="true">→</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
```

**運用イメージ：** 管理画面で固定ページ「訪問看護」を開く → 「料金表PDF」フィールドに新しいPDFをアップロード → 公開。URL は自動更新。

---

## 2. 訪問看護 - ギャラリー

**表示条件：** Page = `訪問看護`

| フィールド名 | キー | タイプ | 必須 | 備考 |
|--------------|------|--------|------|------|
| ギャラリー画像 | `houkan_gallery` | Gallery | ○ | 推奨枚数: 6〜12枚。順序ドラッグで変更可 |

**現状HTML（参考）：** `service/houkan/index.html:187-200`（同じ画像配列を2回繰り返してループ表現）

**テンプレート実装例：**
```php
<?php $gallery = get_field('houkan_gallery'); ?>
<?php if ($gallery): ?>
<section class="svc-gallery fade-in">
  <div class="container">
    <div class="svc-gallery__header">
      <h2 class="svc-gallery__title">gallery</h2>
      <p class="svc-gallery__subtitle">日々の様子</p>
    </div>
    <div class="svc-gallery__grid">
      <div class="svc-gallery__track">
        <?php
        // ループ用に2回繰り返す（無限スライダー対応）
        for ($i = 0; $i < 2; $i++):
          foreach ($gallery as $image): ?>
            <div class="svc-gallery__item" style="background-image:url('<?= esc_url($image['url']) ?>')"></div>
          <?php endforeach;
        endfor; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
```

**運用イメージ：** 「ギャラリー画像」フィールド → 「画像を追加」 → メディアライブラリから複数選択 → ドラッグで並び替え → 公開。

---

## 3. グループホーム - ギャラリー

**表示条件：** Page = `グループホーム`

| フィールド名 | キー | タイプ | 必須 | 備考 |
|--------------|------|--------|------|------|
| ギャラリー画像 | `grouphome_gallery` | Gallery | ○ | 推奨枚数: 6〜12枚 |

**現状HTML（参考）：** `service/grouphome/index.html:169-182`

**実装例：** §2 と同じ構造（プレフィックス `grouphome_` に置換）

---

## 4. グループホーム - ホーム一覧（拠点カード）

**表示条件：** Page = `グループホーム`

| フィールド名 | キー | タイプ | 必須 | 備考 |
|--------------|------|--------|------|------|
| ホーム一覧 | `grouphome_homes` | Repeater | ○ | サブフィールド ↓ |

**Repeater サブフィールド：**

| サブキー | タイプ | 必須 | 備考 |
|----------|--------|------|------|
| `name` | Text | ○ | 例: `あんじゅ大森南` |
| `address` | Text | ○ | 例: `東京都大田区大森南3-29-10` |
| `image_left` | Image | ○ | カード左側画像 |
| `image_right` | Image | ○ | カード右側画像 |

**現状HTML（参考）：** `service/grouphome/index.html:104-152`（4拠点ハードコード）

**テンプレート実装例：**
```php
<?php if (have_rows('grouphome_homes')): ?>
<div class="svc-homes">
  <?php while (have_rows('grouphome_homes')): the_row();
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
  <?php endwhile; ?>
</div>
<?php endif; ?>
```

**運用メリット：** 新しい拠点を追加する場合、管理画面で「行を追加」→ 名称・住所・画像2枚を入力 → 公開。HTML編集不要。

---

## ACF フィールドグループのインポート用 JSON

WordPress 移行時、ACF 管理画面から「ツール → フィールドグループをインポート」で読み込めるよう、JSON ひな形を別途作成可能。必要であれば `docs/acf-fields.json` として用意する。

---

## ACF 無料版で進める場合の代替案

ACF Pro が予算的に難しい場合：

| 機能 | Pro | 無料版での代替 |
|------|-----|----------------|
| File（PDF）フィールド | ○ | ○（無料版でも標準） |
| Gallery フィールド | ○ | × → Gutenberg ネイティブの「Gallery ブロック」を固定ページ本文に配置し、ショートコードや `the_content()` で取得 |
| Repeater フィールド | ○ | × → 「Carbon Fields」「Meta Box Lite」等の代替プラグイン or Gutenberg の「再利用ブロック / 同期パターン」 |

**推奨：** PDF管理（§1）だけなら無料版で十分。ギャラリー・Repeater が必要なら Pro を購入。

---

## 移行時の注意事項

1. **画像/PDFの引き継ぎ：** 静的サイトの `assets/images/` `assets/docs/` 配下のファイルを WP メディアライブラリに一括インポート（プラグイン: Add From Server / WP Bulk Import 等）
2. **URL構造：** `/service/houkan/` を固定ページの permalink に合わせる（カスタムリライトで対応可能）
3. **CSS：** 現状の `assets/css/style.css` をテーマの `style.css` または `assets/` 配下に移行。BEM のクラス名は変更不要
4. **キャッシュ：** WP 移行後は WP Rocket / W3 Total Cache 等を導入し、画像CDN（Cloudflare Images / Cloudinary）連携で表示速度維持

---

## 関連ファイル

- 静的サイト本番ブランチ：`corp/anju`
- 関連ファイル一覧：
  - PDF: `service/houkan/index.html` (L138-175)
  - 訪問看護ギャラリー: `service/houkan/index.html` (L180-202)
  - グループホームギャラリー: `service/grouphome/index.html` (L162-185)
  - ホーム一覧: `service/grouphome/index.html` (L104-152)
