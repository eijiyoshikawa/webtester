# 📚 合同会社あんじゅ Web サイト ドキュメント集

合同会社あんじゅ コーポレートサイトの **WordPress 移行準備** および **運用** に関するドキュメント一覧。

---

## ドキュメント全体図

| # | ファイル | 用途 | 対象読者 | フォーマット |
|---|---------|------|---------|-------------|
| 1 | [wordpress-rfp-template.md](./wordpress-rfp-template.md) | **RFP（要件定義書）** — 制作会社への発注用 | 発注者・営業 | Markdown |
| 2 | [wordpress-acf-spec.md](./wordpress-acf-spec.md) | **カスタムフィールド設計書** — Carbon Fields / ACF 共通 | WP開発者 | Markdown |
| 3 | [acf-fields.json](./acf-fields.json) | ACF Pro 用インポートJSON（バックアップ参考） | WP開発者 | JSON |
| 4 | [image-migration-plan.md](./image-migration-plan.md) | **画像・PDF移行計画** — WP-CLI手順含む | WP開発者・インフラ | Markdown |
| 5 | [launch-checklist.md](./launch-checklist.md) | **ローンチ前チェックリスト** — DNS切替・SEO検証 | PM・全関係者 | Markdown |
| 6 | [site-operations-manual.md](./site-operations-manual.md) | **運用マニュアル** — 日常的な更新方法 | 納品先従業員 | Markdown（WP固定ページ化予定） |

## WordPress テーマ実装

`wp-theme/anju/` に **完全動作する WordPress テーマ** を同梱。

| バージョン | プラグイン | コスト |
|----------|-----------|--------|
| **`wp-theme/anju/`（メイン）** | **Carbon Fields**（OSS） | **完全無料** |
| `wp-theme/anju-acf-backup/`（参考） | ACF Pro | 年額 $49〜 |

詳細は [`wp-theme/anju/README.md`](../wp-theme/anju/README.md) 参照。

---

## 利用シナリオ別ガイド

### 🎯 これから WordPress 移行を発注する場合

1. **[wordpress-rfp-template.md](./wordpress-rfp-template.md)** をベースに RFP を作成
   - 案件概要・予算・スケジュールを記入
   - 提案を募る制作会社へ送付
2. 添付資料として制作会社へ以下も渡す：
   - [wordpress-acf-spec.md](./wordpress-acf-spec.md)
   - [acf-fields.json](./acf-fields.json)
   - [image-migration-plan.md](./image-migration-plan.md)

### 🛠 WordPress 移行作業を実施する場合（制作会社・開発者向け）

1. **[wordpress-acf-spec.md](./wordpress-acf-spec.md)** で全体設計を理解
2. **[acf-fields.json](./acf-fields.json)** を ACF 管理画面からインポート
3. **[image-migration-plan.md](./image-migration-plan.md)** に従って画像・PDF移行
4. **[launch-checklist.md](./launch-checklist.md)** で本番リリース前を検証

### 📝 サイト運用を担当する場合（納品後）

1. **[site-operations-manual.md](./site-operations-manual.md)** を WordPress の固定ページとして設置
   - 公開状態は **パスワード保護**
   - メニュー非表示、URL直アクセスのみ
2. ニュース更新・画像差し替え・PDF差し替えはマニュアル参照

---

## 現状サイト構成

### リポジトリ
- **リポジトリ：** `eijiyoshikawa/webtester`
- **本番ブランチ：** `corp/anju`（Vercel が自動デプロイ）
- **作業ブランチ：** `claude/add-image-paths-25kBC`（自動コミット用）

### サイト構成（静的HTML）
```
/home/user/webtester/
├── index.html                    トップページ
├── service/
│   ├── grouphome/index.html      グループホームページ
│   └── houkan/index.html         訪問看護ページ
├── news/index.html               お知らせ
├── blog/index.html               ブログ
├── contact/index.html            お問い合わせ
├── privacy-policy/index.html     プライバシーポリシー
├── assets/
│   ├── css/style.css             スタイル
│   └── images/                   画像（92ファイル）
└── docs/                         本ドキュメント群
```

### 現状の運用フロー
- 編集 → Claude Code の auto-commit hook → `corp/anju` へ自動push → Vercel自動デプロイ
- 詳細は `.claude/hooks/auto-commit.sh`

---

## WordPress 移行スコープ概要

### 目的
- 静的 HTML → WordPress + ACF Pro 化
- **デザインは現状サイトをそのまま再現**（リデザインなし）
- 編集スタッフが管理画面のみで全更新可能

### 主要機能
- ACF カスタムフィールド：4グループ・36フィールド
- カスタム投稿タイプ：お知らせ・ブログ
- お問い合わせフォーム：Contact Form 7 + reCAPTCHA + WP Mail SMTP
- 全画像・PDFが管理画面から差し替え可能
- 拠点情報（Repeater）で追加・削除も可能

### 必須プラグイン
| プラグイン | 用途 |
|-----------|------|
| Advanced Custom Fields PRO | カスタムフィールド |
| Contact Form 7 | お問い合わせフォーム |
| Flamingo | フォーム送信履歴保存 |
| WP Mail SMTP | メール送信信頼性 |
| reCAPTCHA | スパム対策 |
| Yoast SEO（or All in One SEO） | SEO |
| UpdraftPlus | バックアップ |
| Wordfence（or SiteGuard） | セキュリティ |
| WP Rocket（or W3 Total Cache） | キャッシュ |
| ShortPixel（or EWWW） | 画像最適化 |
| Redirection | URL リダイレクト |
| Enhanced Media Library | メディア整理 |

---

## 重要連絡先

| 役割 | 氏名・連絡先 |
|------|--------------|
| 発注元 担当者 | （氏名・メール・電話） |
| 制作会社 | （社名・PM氏名・メール・電話） |
| ホスティング | （サービス名・サポート連絡先） |
| ドメイン管理 | （レジストラ名・ログイン情報の保管場所） |

---

## 改訂履歴

| 版 | 日付 | 更新者 | 内容 |
|----|------|--------|------|
| 1.0 | 2026.04.29 | 制作会社 | ドキュメント群初版作成 |

---

## ライセンス・利用範囲

本ドキュメントは合同会社あんじゅ Web サイトの移行・運用のために作成された内部資料です。
社外への共有・公開は事前承認のうえ実施してください。

---

> 本ドキュメント群に関するご質問は、上記連絡先までお問い合わせください。
