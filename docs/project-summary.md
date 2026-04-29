# プロジェクト整理ドキュメント

> 合同会社あんじゅ Web サイト 静的HTML→WordPress 移行プロジェクトのこれまでの作業内容まとめ。
>
> **最終更新：** 2026年4月29日

---

## 📌 プロジェクト概要

**目的：** 静的 HTML サイトを WordPress 化し、社内スタッフが画像・テキストを管理画面から自由に編集できるようにする。

**リポジトリ：** `eijiyoshikawa/webtester`
**本番ブランチ：** `corp/anju`（Vercel が自動デプロイ）
**作業ブランチ：** `claude/add-image-paths-25kBC`

---

## 1️⃣ 静的サイトの修正履歴

WordPress 移行前に実施した既存サイトの修正：

| 対象 | 修正内容 |
|------|---------|
| philosophy セクション 大画像 | `secondsection.jpg` に差し替え + 「詳しくはこちら」CTA削除 |
| philosophy 右下画像 | `gh-staff-group-04.jpg` 差し替え（軽量化） |
| philosophy 左下画像 | `gh-caregivers-02.jpg` 差し替え（軽量化） |
| NEWS セクション | 「2026.04.29 HPを刷新しました」の1件のみに整理 |
| サービスロゴ | `logo-grouphome.png` / `logo-houkan.png` 差し替え |
| サービスセクション全体 | 白パネル + 細枠カード化、ロゴ背景白に変更 |
| サービスセクション背景 | `--cream` → `--peach`（our-service と連続化） |
| サービスセクション間 padding | 縮小（100px → 40px） |
| お問い合わせセクション | Google Maps 埋め込み全ページから削除 → 中央寄せシンプル配置 |
| トップページ ヒーロー1枚目 | `grouphome-hero.jpg` に変更 |
| あんじゅ大森南 左側写真 | `grouphome-hero.jpg` に変更 |
| あんじゅ大岡山② 左側写真 | `gh-washroom-01.jpg` に変更 |
| あんじゅ西蒲田 右側写真 | `nishikamata-kitchen-02.jpg` 差し替え |
| /service/grouphome メイン写真 | `omori-exterior-front.jpg` 差し替え |
| グループホームギャラリー | `meeting-06.jpg` 追加 |

---

## 2️⃣ 自動化環境の構築

| 項目 | 内容 |
|------|------|
| auto-commit フック | `Edit/Write/MultiEdit/NotebookEdit` 後に自動 commit + push |
| Push 先 | `corp/anju`（本番）+ 作業ブランチ |
| 設定ファイル | `.claude/hooks/auto-commit.sh` + `.claude/settings.json` |
| ログ | `/tmp/claude-auto-commit.log` |

---

## 3️⃣ WordPress 移行ドキュメント整備

`docs/` フォルダに以下7ファイルを配置：

| # | ファイル | 用途 | 対象読者 |
|---|---------|------|---------|
| 1 | `README.md` | ドキュメント目次 | 全員 |
| 2 | `wordpress-rfp-template.md` | RFP（要件定義書）テンプレート | 発注者・営業 |
| 3 | `wordpress-acf-spec.md` | カスタムフィールド設計書 | WP開発者 |
| 4 | `acf-fields.json` | ACF Pro 用インポートJSON（参考） | WP開発者 |
| 5 | `image-migration-plan.md` | 画像/PDF移行計画（WP-CLI手順含む） | WP開発者・インフラ |
| 6 | `launch-checklist.md` | ローンチ前チェックリスト（DNS切替・SEO） | PM・全関係者 |
| 7 | `site-operations-manual.md` | 運用マニュアル（納品先従業員向け） | エンドユーザー |

---

## 4️⃣ WordPress テーマの構築

### テーマ構造

| 項目 | 内容 |
|------|------|
| テーマ名 | **Anju** |
| テーマフォルダ | `wp-theme/anju/` |
| バージョン | 1.0.0 |
| カスタムフィールド | **Carbon Fields**（完全無料） |
| バックアップ | `wp-theme/anju-acf-backup/`（ACF Pro 版・参考） |
| 総ファイル数 | PHP 22 + Carbon Fields vendor + 画像92 |
| サイズ | 約 216MB（vendor + 画像込み） |

### テンプレートファイル一覧

```
wp-theme/anju/
├── style.css                       # テーマヘッダ
├── functions.php                   # エントリポイント
├── composer.json                   # Carbon Fields 依存定義
├── README.md                       # インストール手順
├── header.php / footer.php
├── front-page.php                  # トップページ
├── page-houkan.php                 # 訪問看護
├── page-grouphome.php              # グループホーム
├── page-contact.php                # お問い合わせ（CF7連携）
├── page-thanks.php                 # 完了
├── page-privacy-policy.php
├── page-manual.php                 # 運用マニュアル（パスワード保護）
├── archive-news.php                # お知らせ一覧
├── single-news.php                 # お知らせ詳細
├── home.php                        # ブログ一覧
├── single.php                      # ブログ詳細
├── page.php / index.php / 404.php / search.php / searchform.php
├── inc/
│   ├── theme-setup.php
│   ├── enqueue.php
│   ├── post-types.php              # カスタム投稿タイプ「news」
│   ├── carbon-fields-setup.php     # ★全フィールド定義
│   └── helpers.php
├── template-parts/
│   └── contact-cta.php
├── vendor/                         # Carbon Fields 同梱（12MB）
└── assets/
    ├── css/style.css
    ├── js/main.js
    └── images/                     # 92ファイル
```

### Carbon Fields フィールドグループ

| グループ | 表示条件 | 主なフィールド |
|---------|---------|---------------|
| トップページ設定 | front-page / page-home.php | ヒーロー / philosophy / 代表メッセージ / サービスロゴ |
| 訪問看護ページ設定 | page-houkan.php | メイン写真 / PDF（料金・サービス）/ ギャラリー |
| グループホームページ設定 | page-grouphome.php | メイン写真 / ホーム一覧（Repeater）/ ギャラリー |
| お問い合わせページ設定 | page-contact.php | CF7 ショートコード |
| サイト共通設定 | Theme Options | ロゴ / 連絡先 / SNS |

---

## 5️⃣ WordPress インストール状況

### ✅ 完了したこと

- [x] テーマ ZIP アップロード・有効化
- [x] Carbon Fields vendor 同梱で動作確認

### 🔲 これから実施すること

#### A. 必須プラグインの導入
- [ ] **Contact Form 7**（お問い合わせフォーム）
- [ ] **Flamingo**（送信履歴保存）
- [ ] **WP Mail SMTP**（メール送信信頼性）
- [ ] **reCAPTCHA**（スパム対策）

#### B. 推奨プラグイン
- [ ] **UpdraftPlus**（自動バックアップ）
- [ ] **Wordfence** または **SiteGuard WP Plugin**（セキュリティ）
- [ ] **Two-Factor**（2段階認証）
- [ ] **Yoast SEO**（SEO設定）
- [ ] **Redirection**（URLリダイレクト）
- [ ] **WP Rocket** または **W3 Total Cache**（キャッシュ）
- [ ] **ShortPixel** または **EWWW**（画像最適化）

#### C. 固定ページの作成

> **作成前に必ず：** 設定 → パーマリンク → **「投稿名」** に変更

| # | ページタイトル | スラッグ | 親ページ | 公開状態 |
|---|--------------|---------|---------|----------|
| 1 | ホーム | `home` | （なし） | 公開 |
| 2 | Service | `service` | （なし） | 公開 |
| 3 | 訪問看護 | `houkan` | Service | 公開 |
| 4 | グループホーム | `grouphome` | Service | 公開 |
| 5 | お問い合わせ | `contact` | （なし） | 公開 |
| 6 | お問い合わせ完了 | `thanks` | お問い合わせ | 公開 |
| 7 | プライバシーポリシー | `privacy-policy` | （なし） | 公開 |
| 8 | 運用マニュアル | `manual` | （なし） | **🔒 パスワード保護** |
| 9 | ブログ | `blog` | （なし） | 公開 |

#### D. 表示設定の調整

- [ ] 設定 → 表示設定 → ホームページの表示
  - ホームページ：「ホーム」を選択
  - 投稿ページ：「ブログ」を選択

#### E. 画像のメディアライブラリ移行

- [ ] テーマ同梱の `assets/images/` 内 92画像を WP メディアライブラリに一括アップロード
  - 推奨：管理画面 → メディア → 新規追加 → ドラッグ&ドロップ一括
  - または FTP + 「Add From Server」プラグイン
- [ ] 主要画像に ALT テキスト設定

#### F. Carbon Fields でコンテンツ入力

- [ ] 「ホーム」固定ページ → 各タブ（ヒーロー/philosophy/メッセージ/ロゴ）に画像・テキスト
- [ ] 「訪問看護」固定ページ → メイン写真・PDF・ギャラリー
- [ ] 「グループホーム」固定ページ → メイン写真・ホーム一覧（4拠点）・ギャラリー
- [ ] 「お問い合わせ」→ CF7 ショートコード
- [ ] 「運用マニュアル」→ 本文に運用マニュアル内容貼付（`docs/site-operations-manual.md` から）+ パスワード保護
- [ ] サイドバー「サイト共通設定」→ ロゴ・連絡先・SNS入力

---

## 6️⃣ 完成するURL構造

```
https://example.com/                        → トップページ
https://example.com/service/                → Service 親ページ
https://example.com/service/houkan/         → 訪問看護 ★
https://example.com/service/grouphome/      → グループホーム ★
https://example.com/contact/                → お問い合わせ
https://example.com/contact/thanks/         → 完了
https://example.com/privacy-policy/         → プライバシーポリシー
https://example.com/manual/                 → 運用マニュアル（パスワード保護）
https://example.com/blog/                   → ブログ一覧
https://example.com/news/                   → お知らせ一覧（カスタム投稿タイプ自動）
```

---

## 7️⃣ 動作の仕組み（管理画面 ↔ 表示）

```
┌────────────────────────────────────────┐
│  Carbon Fields に値が入っている？     │
└──────────────┬─────────────────────────┘
               │
       ┌───────┴───────┐
       │               │
      YES             NO
       │               │
       ▼               ▼
  入力した画像     テーマ同梱の
  /テキストを     デフォルト画像
  表示            （現状の見た目）
```

- **何も触らない** → 現状デザインのまま表示
- **差し替えたい時だけ** → 該当フィールドに新規アップロード → 即反映
- **間違えてフィールド空にしても** → デフォルト画像にフォールバック（サイト崩れない）

---

## 8️⃣ 主な GitHub URL

| 用途 | URL |
|------|-----|
| リポジトリ TOP | https://github.com/eijiyoshikawa/webtester |
| corp/anju ブランチ | https://github.com/eijiyoshikawa/webtester/tree/corp/anju |
| docs フォルダ | https://github.com/eijiyoshikawa/webtester/tree/corp/anju/docs |
| WordPress テーマ | https://github.com/eijiyoshikawa/webtester/tree/corp/anju/wp-theme/anju |
| ACF Pro バックアップ | https://github.com/eijiyoshikawa/webtester/tree/corp/anju/wp-theme/anju-acf-backup |
| ZIP ダウンロード | リポジトリ TOP → Code → Download ZIP |

---

## 9️⃣ よくある質問・対処法

### Q1：manualページが空白なんだけど？
→ 固定ページの本文が空 + パスワード保護未設定の状態です。
1. 編集画面の「公開状態」を **パスワード保護** に変更
2. 本文エリアに `docs/site-operations-manual.md` の内容を貼付
3. 「更新」で保存

### Q2：管理画面に「Carbon Fields がない」と警告が出る
→ vendor/ フォルダがアップロードされていません。最新の `corp/anju` を再ダウンロードして再インストール。

### Q3：画像差し替えしたいけど Carbon Fields のフィールドにメディアライブラリの画像が出ない
→ 画像をまずメディアライブラリにアップロードする必要があります。
管理画面 → メディア → 新規追加 → ドラッグ&ドロップ。

### Q4：固定ページの URL が `/?p=123` のようになる
→ パーマリンク設定を確認。設定 → パーマリンク → **「投稿名」** に変更 → 保存。

### Q5：カスタムフィールドに値を入れても反映されない
→ キャッシュプラグインのキャッシュが残っている可能性。プラグイン側でキャッシュクリア。

---

## 🔟 既知の制約・留意事項

1. **CSS で背景画像を直接参照している箇所は Carbon Fields で完全には差し替えられない**
   - 例：philosophy セクションの「`gh-caregivers-02.jpg`」「`gh-staff-group-04.jpg`」（CSS の `:nth-child` で個別指定）
   - 対処：これらは旧静的サイト時代の構成。Carbon Fields のフィールドが空でも CSS のデフォルトが効く設計
   - 完全動的化したい場合は `wp_add_inline_style()` で CSS を上書きする実装が必要

2. **ヒーロースライダーのデフォルトは1枚のみ表示**
   - Carbon Fields の `home_hero_slides` フィールドが空の場合、`grouphome-hero.jpg` 1枚だけ表示
   - 複数枚スライドさせたい場合は必ず複数枚アップロード

3. **画像のメディアライブラリ移行は手動**
   - テーマ有効化だけでは画像はメディアライブラリには追加されない
   - 手動アップロード or プラグイン利用が必要

4. **Composer 不要設計だが、サイズが大きい**
   - vendor/ 同梱で 12MB 増加。WP管理画面でテーマZIPをアップロードする際の上限に注意
   - 上限超過時は php.ini で `upload_max_filesize` を調整 or FTP

5. **本番動作確認は実機必須**
   - 本テーマの PHP 構文は検証済みだが、WordPress + Carbon Fields の実機での全機能動作は実環境でのテスト必須

---

## 📞 サポート・連絡先

| 項目 | 連絡先 |
|------|--------|
| システム管理者 | （担当者名・メール・電話） |
| 制作会社 | （社名・PM・メール・電話） |
| ホスティング | （サービス名・サポート連絡先） |

---

## 📅 改訂履歴

| 版 | 日付 | 内容 |
|----|------|------|
| 1.0 | 2026.04.29 | 初版作成（プロジェクト全体整理） |

---

## 🎯 全体タイムライン（参考）

```
[完了済み]
2026.04.28 静的サイト修正・WordPress テーマ構築・Carbon Fields 化
2026.04.29 vendor 同梱・テーマアップロード・初期確認

[これから]
- 固定ページ作成（9ページ）
- 必須プラグイン導入
- メディアライブラリへの画像移行
- Carbon Fields でコンテンツ入力
- お問い合わせフォーム設定
- セキュリティ・バックアップ設定
- ローンチ前チェックリスト確認
- DNS切替（or 同URLで直接公開）
```

---

**📝 このドキュメントについて：**
本ドキュメントはプロジェクト全体の現状把握用に作成された整理資料です。
詳細は `docs/` フォルダ内の各ファイルを参照してください。
