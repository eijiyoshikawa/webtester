# TECNES Recruit — WordPress テーマ

千代田工販株式会社 新卒採用LP（[tecnes_lp_001](https://github.com/hmatsuoka-glitch/tecnes_lp_001) / Next.js 14版）の **オリジナル WordPress テーマ移植版**です。

オリジナル Next.js プロジェクト内の `wordpress-theme/tecnes-recruit/` をベースに、軽微なクリーンアップを加えた上で `wp-theme/tecnes-recruit/` に配置しています。

## 構成

```
wp-theme/tecnes-recruit/
├── style.css                 … テーマヘッダー (必須)
├── functions.php             … 設定 / アセット enqueue / ヘルパー関数 (tecnes_img)
├── header.php                … 共通ヘッダー（PC ナビ + ハンバーガー + ENTRY ドロップダウン）
├── footer.php                … 共通フッター（サイトマップ + 会社情報 + アクセス）
├── front-page.php            … TOP（全セクションを get_template_part で連結）
├── index.php                 … フォールバック（front-page と同内容）
├── template-parts/
│   ├── main-visual.php       … MV (Splide スライダー)
│   ├── read.php              … リード文 + 縦長画像
│   ├── business.php          … 事業紹介カード + モーダル（JSON 経由でJSに渡す）
│   ├── flow.php              … 仕事の流れ図 (PC: 静止画 / SP: 横スクロール)
│   ├── about.php             … 数字カード（IntersectionObserver でカウントアップ）
│   ├── discovery.php         … 背景画像セクション
│   ├── people.php            … 社員紹介 Swiper カルーセル
│   ├── environment.php       … 環境紹介グリッド
│   ├── footnav.php           … フッター上部 CTA
│   └── gnav.php              … モバイル用ドロワー
└── assets/
    ├── css/   (13 files: globals + 12 section CSS)
    ├── js/    (6 files: header / mainvisual / business-modal / people-swiper / about-counter / scroll-animator)
    ├── vendor/ (Splide & Swiper CSS)
    └── images/ (94 ファイル)
```

## レスポンシブ / 画像チェック結果

事前に静的解析を実施済み：

- ✅ `header.php:9` に viewport meta あり（`width=device-width, initial-scale=1.0, minimum-scale=1.0`）
- ✅ メディアクエリ計31個：`globals.css` に9個（`max-width:374px` / `max-width:767px` SP / `max-width:1024px` タブレット / `min-width:768/1025/1281px` PC）、各セクションCSSに2-3個ずつ
- ✅ 全 PHP ファイルで参照される **49枚すべての画像** が `assets/images/` に存在（欠落ゼロ）
- ✅ PHP 構文エラーなし

## セットアップ手順

1. **テーマ配置**
   `wp-theme/tecnes-recruit/` を WordPress の `wp-content/themes/tecnes-recruit/` に丸ごとコピー。

2. **テーマ有効化**
   管理画面 → 外観 → テーマ → 「TECNES Recruit」を有効化。

3. **表示設定**
   管理画面 → 設定 → 表示設定 → 「ホームページの表示」を **最新の投稿** または、**固定ページ（任意の空ページを front-page として割り当て）** に設定すれば `front-page.php` が描画されます。

4. **パーマリンク**
   管理画面 → 設定 → パーマリンク → 「投稿名」(`/%postname%/`) を保存。

## 仕様メモ

- **Splide / Swiper** は CDN (jsDelivr) と同梱の `assets/vendor/` の両方で読み込まれます。CSP の都合でCDNを切りたい場合は `functions.php` の `wp_enqueue_script('splide', ...)` / `wp_enqueue_script('swiper', ...)` を同梱版に差し替えてください。
- **画像** は `tecnes_img( 'filename.ext' )` ヘルパーで参照（`functions.php:97`）。`get_template_directory_uri() . '/assets/images/'` を組み立てます。
- **ナビゲーション** の `/people/`, `/culture/`, `/recruitment/` 等のリンクは下層ページを想定。1枚LPで運用する場合は TOP 内のアンカー (`#business`, `#flow`, `#people` 等) にリダイレクトされる構成になっています。
- **フォント**: Noto Sans JP + Poppins を Google Fonts から読み込み（`functions.php:38`）。

## オリジナルからの変更点

- `assets/css/globals.css` 先頭の未コンパイル `@tailwind base/components/utilities` 3行を削除（Next.js 開発環境専用のディレクティブで、ブラウザでは無視される。実際のマークアップでは Tailwind ユーティリティを使っていないため影響なし）。
