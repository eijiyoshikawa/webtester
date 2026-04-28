# Anju WordPress Theme

合同会社あんじゅ コーポレートサイト用 WordPress テーマ。
**Carbon Fields**（完全無料）を使用してカスタムフィールドを実装。

## 必要環境

- **WordPress 6.0 以上**
- **PHP 8.0 以上**
- **Carbon Fields**（必須）— Composer でインストール、本テーマに同梱の `composer.json` 使用
- **Contact Form 7 + Flamingo**（推奨：お問い合わせフォーム）
- **WP Mail SMTP**（推奨：メール送信信頼性）

## なぜ Carbon Fields？

ACF Pro（年額 $49〜の有料プラグイン）の代わりに **Carbon Fields**（完全無料・OSS）を採用。
- 編集者の管理画面UIは ACF Pro とほぼ同等
- Repeater / Gallery / Theme Options をすべて標準サポート
- フィールド定義が PHP コードベース（テーマに含まれる `inc/carbon-fields-setup.php`）
- ライセンス更新・サブスク不要

## インストール手順

### 1. テーマファイルをサーバーへ配置

`wp-theme/anju/` フォルダを `wp-content/themes/anju/` に配置（FTP/SCP/Git）。

### 2. Composer で Carbon Fields をインストール

サーバーの SSH 経由でテーマディレクトリへ移動し、Composer を実行：

```bash
cd /path/to/wp-content/themes/anju/
composer install
```

これで `vendor/` ディレクトリが作成され、Carbon Fields が使用可能になります。

> **Composer が使えない環境の場合：**
> ローカルで `composer install` を実行 → 生成された `vendor/` ディレクトリごとサーバーへアップロード

### 3. テーマを有効化

WordPress 管理画面 → 外観 → テーマ → 「Anju」を有効化

### 4. 固定ページの作成と割り当て

WordPress管理画面 → 固定ページ → 新規追加 で以下のページを作成し、各ページに対応するテンプレートを割り当ててください。

| ページタイトル | スラッグ | テンプレート | 備考 |
|---------------|----------|--------------|------|
| Home | `home` | （front-page.php が自動適用） | 「設定 → 表示設定」で**フロントページとして固定** |
| 訪問看護 | `houkan` | 訪問看護ページ | サービスページ |
| グループホーム | `grouphome` | グループホームページ | サービスページ |
| お問い合わせ | `contact` | お問い合わせページ | Contact Form 7 ショートコードを設定 |
| お問い合わせ完了 | `thanks` | お問い合わせ完了 | CF7の送信後リダイレクト先 |
| プライバシーポリシー | `privacy-policy` | プライバシーポリシー | デフォルト値あり |
| 運用マニュアル（社内用） | `manual` | 運用マニュアル（社内用） | **公開状態をパスワード保護に設定** |

#### URL構造の調整

旧静的サイトと同じURL構造（`/service/houkan/`、`/service/grouphome/`）を維持したい場合：

**方法A：固定ページのスラッグで親子構造を作る**
- 親ページ「service」を作成
- 「訪問看護」のスラッグを `houkan`、親ページを「service」に → URL が `/service/houkan/` になる

**方法B：Redirection プラグインで301リダイレクト**

### 5. カスタムフィールドの初期入力

テーマを有効化し Composer install が完了すると、各ページの編集画面に Carbon Fields のフィールド群が自動表示されます。

1. 管理画面 → 「Home」固定ページを編集
2. ページ下部の Carbon Fields タブ群が表示される（ヒーロー / philosophy / 代表メッセージ / サービスロゴ）
3. 各タブで画像アップロード・テキスト入力 → 公開
4. 同じく「訪問看護」「グループホーム」も入力
5. 管理画面サイドバー → 「サイト共通設定」で SNS URL・連絡先を入力

### 6. メディアライブラリへの画像移行

旧静的サイトの `assets/images/` を WP メディアライブラリへ移行する手順は別ドキュメント `docs/image-migration-plan.md` 参照。

WP-CLI 利用可能なら以下が最速：

```bash
cd /var/www/html/  # WPルート
for f in /path/to/wp-content/themes/anju/assets/images/*.{jpg,png,gif}; do
  [ -f "$f" ] && wp media import "$f"
done
```

### 7. お問い合わせフォーム設定

1. Contact Form 7 をインストール・有効化
2. 「お問い合わせ → コンタクトフォーム」で新規フォームを作成
3. 「メール」タブで送信先メールアドレスを設定
4. ショートコード（`[contact-form-7 id="..."]`）をコピー
5. 「お問い合わせ」固定ページ編集 → 「Contact Form 7 ショートコード」フィールドへ貼り付け
6. 送信後リダイレクトURLを `/thanks/` に設定（CF7「追加設定」タブで `on_sent_ok: "location.replace('/thanks/');"`）

### 8. SMTP（メール送信）設定

WP Mail SMTP プラグインで Gmail / SendGrid / Amazon SES 等を設定。

### 9. セキュリティ

- 管理者アカウント：強パスワード必須、2要素認証推奨
- WordPress / プラグインは常に最新
- Wordfence または SiteGuard プラグイン推奨

## ディレクトリ構成

```
anju/
├── style.css                       # テーマヘッダ
├── functions.php                   # エントリポイント（Carbon Fields autoload）
├── composer.json                   # Carbon Fields 依存定義
├── README.md                       # 本ファイル
├── header.php / footer.php
├── front-page.php                  # トップページ
├── page-houkan.php                 # 訪問看護
├── page-grouphome.php              # グループホーム
├── page-contact.php                # お問い合わせ
├── page-thanks.php                 # 完了
├── page-privacy-policy.php
├── page-manual.php                 # 運用マニュアル（パスワード保護）
├── archive-news.php                # お知らせ一覧
├── single-news.php                 # お知らせ詳細
├── home.php                        # ブログ一覧
├── single.php                      # ブログ詳細
├── page.php / index.php            # フォールバック
├── 404.php / search.php / searchform.php
├── inc/
│   ├── theme-setup.php             # テーマサポート
│   ├── enqueue.php                 # CSS/JS / Google Fonts
│   ├── post-types.php              # カスタム投稿タイプ「news」
│   ├── carbon-fields-setup.php     # ★全フィールド定義
│   └── helpers.php                 # ヘルパー関数
├── template-parts/
│   └── contact-cta.php             # 共通コンタクトCTA
├── vendor/                         # Composer install で生成（gitignore推奨）
└── assets/
    ├── css/style.css
    ├── js/main.js
    └── images/                     # 92ファイル
```

## Carbon Fields フィールドグループ

| グループ | 表示条件 | フィールド数 |
|---------|---------|-------------|
| トップページ設定 | テンプレート: page-home.php / フロントページ | 13 |
| 訪問看護ページ設定 | テンプレート: page-houkan.php | 8 |
| グループホームページ設定 | テンプレート: page-grouphome.php | 3+（Complex で拠点を可変追加） |
| お問い合わせページ設定 | テンプレート: page-contact.php | 1 |
| サイト共通設定 | Theme Options Page | 13 |

詳細は `/docs/wordpress-acf-spec.md` 参照。

## カスタム投稿タイプ

- **お知らせ（news）**：トップページ news セクション + `/news/` 一覧で表示
- **ブログ（標準 post）**：`/blog/` で表示

## ヘルパー関数

テンプレートから簡単にフィールド値を取得するためのヘルパー：

```php
// Theme Options
$logo_id = anju_option('site_logo');

// 画像URL（attachment ID → URL に変換）
$logo_url = anju_image_url($logo_id, 'medium');

// 投稿メタ（Complex / Media Gallery にも対応）
$slides = anju_meta('home_hero_slides', null, [], 'simple');  // gallery
$homes  = anju_meta('grouphome_homes',  null, [], 'complex'); // repeater
```

## ACF Pro 版に戻したい場合

`wp-theme/anju-acf-backup/` に ACF Pro 版を保管しています。差し替えればそちらでも運用可。

## ライセンス

合同会社あんじゅ専用テーマ。社外配布・再販不可。
Carbon Fields は MIT License（同梱可）。

## サポート・問い合わせ

このテーマに関する技術的な問い合わせは制作会社まで。
