# Anju WordPress Theme

合同会社あんじゅ コーポレートサイト用 WordPress テーマ。

## 必要環境

- **WordPress 6.0 以上**
- **PHP 8.0 以上**
- **Advanced Custom Fields PRO**（必須）
- **Contact Form 7 + Flamingo**（推奨：お問い合わせフォーム）
- **WP Mail SMTP**（推奨：メール送信信頼性）

## インストール手順

### 1. テーマの ZIP 化

`wp-theme/` フォルダの中の `anju/` ディレクトリを ZIP 圧縮します。

```bash
cd wp-theme
zip -r anju.zip anju/
```

または ZIP 不要なら、サーバーの `wp-content/themes/anju/` に直接アップロード（FTP/SCP）してもOK。

### 2. WordPress 管理画面でアップロード

1. 管理画面 → 外観 → テーマ → 新規追加 → テーマのアップロード
2. `anju.zip` を選択 → アップロード → 有効化

### 3. ACF Pro のインストール

1. プラグイン → 新規追加 → 「Advanced Custom Fields PRO」を検索
2. インストール → 有効化
3. ACF管理画面 → カスタムフィールド → 「フィールドグループ」一覧

> ✅ テーマ有効化と同時に、`acf-json/` 配下のフィールドグループ（4つ）が **自動的に同期** されます。
> 手動インポートは不要です。

### 4. 固定ページの作成と割り当て

WordPress管理画面 → 固定ページ → 新規追加 で以下のページを作成し、各ページに対応するテンプレートを割り当ててください。

| ページタイトル | スラッグ | テンプレート | 備考 |
|---------------|----------|--------------|------|
| Home | `home` | Home（front-page.php が自動適用） | 「設定 → 表示設定」で**フロントページとして固定** |
| 訪問看護 | `houkan` | 訪問看護ページ | サービスページ |
| グループホーム | `grouphome` | グループホームページ | サービスページ |
| お問い合わせ | `contact` | お問い合わせページ | Contact Form 7 ショートコードを設定 |
| お問い合わせ完了 | `thanks` | お問い合わせ完了 | CF7の送信後リダイレクト先 |
| プライバシーポリシー | `privacy-policy` | プライバシーポリシー | デフォルト値あり |
| 運用マニュアル（社内用） | `manual` | 運用マニュアル（社内用） | **公開状態をパスワード保護に設定** |

#### URL構造の調整

旧静的サイトと同じURL構造（`/service/houkan/`、`/service/grouphome/`）を維持したい場合：

**方法A：固定ページのスラッグを変更**
- 「訪問看護」固定ページの編集 → URL を `/service/houkan/` になるように、親ページに `service` を作成して `houkan` を子ページにする

**方法B：Redirection プラグインで301リダイレクト**
- `/houkan/` → `/service/houkan/` のような旧URLを新URLへ

### 5. ACF フィールドの初期入力

1. 管理画面 → 「Home」固定ページを編集
2. ページ下部の ACF タブ群が表示される（ヒーロー / philosophy / message / サービスロゴ）
3. 各タブで画像アップロード・テキスト入力 → 公開
4. 同じく「訪問看護」「グループホーム」も入力
5. 管理画面サイドバー → 「サイト共通設定」で SNS URL・連絡先を入力

### 6. メディアライブラリへの画像移行

旧静的サイトの `assets/images/` を WP メディアライブラリへ移行する手順は別ドキュメント `docs/image-migration-plan.md` 参照。

WP-CLI 利用可能なら以下が最速：

```bash
cd /var/www/html/  # WPルート
for f in /path/to/anju/wp-content/themes/anju/assets/images/*.{jpg,png,gif}; do
  [ -f "$f" ] && wp media import "$f"
done
```

### 7. お問い合わせフォーム設定

1. Contact Form 7 をインストール・有効化
2. 「お問い合わせ → コンタクトフォーム」で新規フォームを作成（既定のフォームでもOK）
3. 「メール」タブで送信先メールアドレスを `[your-email@example.com]` に設定
4. ショートコード（`[contact-form-7 id="..."]`）をコピー
5. 「お問い合わせ」固定ページ編集 → ACF「contact_cf7_shortcode」フィールド（追加可）または本文に貼り付け
6. 送信後リダイレクトURLを `/thanks/` に設定（CF7「追加設定」タブで `on_sent_ok: "location.replace('/thanks/');"` または Redirection プラグイン）

### 8. SMTP（メール送信）設定

WP Mail SMTP プラグインで Gmail / SendGrid / Amazon SES 等を設定。これによりお問い合わせメールの到達率が向上。

### 9. セキュリティ

- 管理者アカウント：強パスワード必須、2要素認証推奨
- WordPress / プラグインは常に最新
- Wordfence または SiteGuard プラグイン推奨

## ディレクトリ構成

```
anju/
├── style.css                    # テーマヘッダ
├── functions.php                # テーマエントリポイント
├── header.php / footer.php
├── front-page.php               # トップページ
├── page-houkan.php              # 訪問看護
├── page-grouphome.php           # グループホーム
├── page-contact.php             # お問い合わせ
├── page-thanks.php              # 完了
├── page-privacy-policy.php
├── page-manual.php              # 運用マニュアル（パスワード保護）
├── archive-news.php             # お知らせ一覧
├── single-news.php              # お知らせ詳細
├── home.php                     # ブログ一覧
├── single.php                   # ブログ詳細
├── page.php / index.php         # フォールバック
├── 404.php / search.php / searchform.php
├── inc/
│   ├── theme-setup.php
│   ├── enqueue.php
│   ├── post-types.php           # カスタム投稿タイプ「news」
│   ├── acf-options.php          # サイト共通設定 Options Page
│   └── helpers.php
├── acf-json/                    # ACFフィールド自動同期
│   ├── group_anju_home.json
│   ├── group_anju_houkan.json
│   ├── group_anju_grouphome.json
│   └── group_anju_site_settings.json
├── template-parts/
│   └── contact-cta.php          # 共通コンタクトCTA
└── assets/
    ├── css/style.css            # メインスタイル
    ├── js/main.js               # フロントスクリプト
    └── images/                  # 画像（92ファイル）
```

## ACF フィールドグループ

| グループ | 表示条件 | フィールド数 |
|---------|---------|-------------|
| トップページ設定 | テンプレート: page-home.php / フロントページ | 13 |
| 訪問看護ページ設定 | テンプレート: page-houkan.php | 8 |
| グループホームページ設定 | テンプレート: page-grouphome.php | 3+ |
| サイト共通設定 | Options Page | 12 |

詳細は `/docs/wordpress-acf-spec.md` 参照。

## カスタム投稿タイプ

- **お知らせ（news）**：トップページ news セクション + `/news/` 一覧で表示
- **ブログ（標準 post）**：`/blog/` で表示

## 既知の制約・留意事項

- **本テーマは ACF Pro が必須**。無料版だと Repeater / Gallery / Options Page フィールドが動作しません
- メイン CSS（`assets/css/style.css`）は静的HTMLサイトのものをそのまま使用。WordPress 環境で再検証推奨
- サービスロゴ（グループホーム/訪問看護）は `assets/css/style.css` 内で `background-image: url('../images/logo-grouphome.png')` のように直接参照されているため、ACF で差し替えても CSS が優先される場合があります。動的化したい場合は `wp_add_inline_style()` で上書きが必要

## ライセンス

合同会社あんじゅ専用テーマ。社外配布・再販不可。

---

## サポート・問い合わせ

このテーマに関する技術的な問い合わせは制作会社まで。
