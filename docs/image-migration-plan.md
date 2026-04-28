# 画像・PDF 移行計画

> 静的サイト `assets/` 配下の画像・PDF を WordPress メディアライブラリへ移行する手順書。
> 移行漏れ・ALT 抜け・URL断線を防ぐためのチェックリスト付き。

---

## 0. 現状サマリー

| 種別 | 件数 | 場所 | 合計サイズ目安 |
|------|------|------|----------------|
| 画像（JPG/PNG/GIF） | 92ファイル | `assets/images/` | 約 50〜80MB（要計測） |
| PDF | 0ファイル | `assets/docs/`（未配置） | — |

> PDFは `service/houkan/index.html` 内で参照されているが実ファイル未配置。WP移行時に新規アップロード予定。

---

## 1. 移行手段の選択肢

| 方式 | 工数 | メリット | デメリット |
|------|------|---------|-----------|
| ① **Add From Server プラグイン** | 低 | サーバー上のファイルをUI経由で一括登録 | サーバーファイルアップロードが必要 |
| ② **Media Sync プラグイン** | 低 | サーバーにアップ済みファイルを自動検出してメディア化 | プラグイン非更新時の不具合リスク |
| ③ **WP-CLI `wp media import`** | 中 | コマンド一発で一括取り込み・自動化可 | サーバーSSH/CLI環境必要 |
| ④ **手動アップロード** | 高 | 最も確実 | 92ファイル×数分=工数大 |

**推奨：方式③（WP-CLI）**。SSH 利用可能ホスティングなら最速・確実。

---

## 2. WP-CLI での一括移行手順（推奨）

### 2.1 事前準備
```bash
# 移行先サーバーへSSH接続
ssh user@your-wp-server.com

# WP-CLI 動作確認
wp --info
```

### 2.2 旧画像をサーバーへ転送
```bash
# ローカルからWP-CLI実行サーバーへ rsync
rsync -avz /home/user/webtester/assets/images/ \
  user@your-wp-server.com:/tmp/anju-images/
```

### 2.3 一括インポート実行
```bash
cd /var/www/html/  # WPルート
for f in /tmp/anju-images/*.{jpg,png,gif,webp}; do
  [ -f "$f" ] && wp media import "$f" --skip-copy=false
done
```

`--skip-copy=false` でファイルを WP メディア標準パス（`wp-content/uploads/YYYY/MM/`）にコピー。

### 2.4 移行検証
```bash
# 件数確認（92件想定）
wp post list --post_type=attachment --format=count

# 重複確認（同名で複数登録されていないか）
wp post list --post_type=attachment --field=post_title | sort | uniq -d
```

### 2.5 不要画像削除（任意）
現状 `assets/images/` の中で、HTML/CSSから一切参照されていないファイルがあれば WP インポート前に削除。
```bash
# 参照チェック例（ローカル）
for img in /home/user/webtester/assets/images/*; do
  name=$(basename "$img")
  if ! grep -rq "$name" /home/user/webtester --include="*.html" --include="*.css"; then
    echo "未使用: $name"
  fi
done
```

---

## 3. 命名規則・整理方針

WP メディアライブラリでは検索性が低いため、以下の規則で整理：

### 3.1 ファイル命名規則（既存準拠）
| プレフィックス | 用途 | 例 |
|---------------|------|-----|
| `gh-` | グループホーム関連 | `gh-cooking-02.jpg` |
| `houkan-` | 訪問看護関連 | `houkan-medicine-01.jpg` |
| `meeting-` | ミーティング・会議 | `meeting-06.jpg` |
| `logo-` | ロゴ画像 | `logo-houkan.png` |
| `omori-` | あんじゅ大森南 | `omori-living-2f.jpg` |
| `okayama1/2-` | あんじゅ大岡山①/② | `okayama1-living.jpg` |
| `nishikamata-` | あんじゅ西蒲田 | `nishikamata-kitchen-02.jpg` |
| `free-` | フリー素材（風景等） | `free-landscape-01.jpg` |

### 3.2 メディアライブラリのカテゴライズ
- **プラグイン推奨：** `Enhanced Media Library`（メディアライブラリにフォルダ機能追加）
- **カテゴリ案：**
  - `01_logo`（ロゴ）
  - `02_hero`（ヒーロー画像）
  - `03_grouphome`
  - `04_houkan`
  - `05_staff`（スタッフ・ミーティング）
  - `06_meal`（食事関連）
  - `07_artwork`（アートワーク）
  - `99_archive`（旧画像・予備）

### 3.3 ALT テキスト（代替テキスト）の整備
WP インポート時、ALT 属性は空のままになります。**SEO・アクセシビリティ確保のため、移行後にすべての画像へ ALT を設定**。

| ファイル名パターン | ALTテキスト案 |
|-------------------|--------------|
| `gh-cooking-*` | あんじゅで料理をするスタッフと利用者 |
| `gh-meal-*` | あんじゅの食事風景 |
| `gh-staff-group-*` | あんじゅのスタッフ集合写真 |
| `houkan-medicine-*` | 訪問看護で薬を確認する様子 |
| `houkan-bloodpressure-*` | 訪問看護で血圧測定をする様子 |
| `meeting-*` | あんじゅのスタッフミーティング |
| `logo-grouphome` | グループホームあんじゅ ロゴ |
| `logo-houkan` | 訪問看護ステーションあんじゅ ロゴ |
| `omori-exterior-*` | あんじゅ大森南 外観 |
| `omori-living-*` | あんじゅ大森南 リビング |

ALT 一括編集には **Bulk Image Alt Text プラグイン** が便利。

---

## 4. PDF 移行・新規配置

### 4.1 PDF配置先（WP）
WP メディアライブラリ → ACF File フィールド経由で参照。

### 4.2 必要ファイル
| ファイル名 | 用途 | フィールド |
|-----------|------|-----------|
| `anju-houkan-price.pdf` | 訪問看護 料金表 | `houkan_price_pdf` |
| `anju-houkan-service.pdf` | 訪問看護 サービス詳細 | `houkan_service_pdf` |

### 4.3 アップロード手順
1. WP管理画面 → メディア → 新規追加 → PDFアップロード
2. 固定ページ「訪問看護」編集 → ACF「料金表PDF」フィールドで該当PDF選択
3. 公開・確認

---

## 5. 画像最適化（移行前/後）

### 5.1 アップロード前の事前圧縮（推奨）

ヒーロー用大型画像（grouphome-hero.jpg 等）は **本番アップロード前に圧縮**。
- [TinyJPG](https://tinyjpg.com/) — JPG/PNG 自動圧縮
- [Squoosh](https://squoosh.app/) — ブラウザで品質調整
- 目安：500KB〜1MB（ヒーロー）、200〜400KB（その他）

### 5.2 自動WebP変換（移行後）
- **ShortPixel** または **EWWW Image Optimizer** プラグイン導入
- 既存JPG/PNG を自動でWebP生成、対応ブラウザにはWebP配信
- ページ読込速度が大幅改善

### 5.3 画像CDN（要件次第）
- Cloudflare Images / Cloudinary などCDN活用も検討
- リクエスト課金モデルなのでアクセス量と相談

---

## 6. URL 置換作業（移行後）

WP 移行で画像URLが変わる：
- 旧：`/assets/images/xxx.jpg`
- 新：`/wp-content/uploads/2026/04/xxx.jpg`

### 6.1 置換不要なケース
ACF フィールド経由で画像を読み込む場合、`get_field()` が新URLを返すため **置換不要**。

### 6.2 置換が必要なケース
固定ページ本文（the_content）内に旧URL文字列で画像が埋め込まれている場合：
- **Better Search Replace** プラグインでDB一括置換

```
検索：/assets/images/
置換：/wp-content/uploads/2026/04/
（Dry Runで確認 → 実行）
```

---

## 7. 移行チェックリスト

### 7.1 事前
- [ ] 移行対象画像の総数・サイズ確認（92件想定）
- [ ] 不要画像（参照なし）の特定・除外判断
- [ ] PDFファイル新規作成（料金表・サービス詳細）
- [ ] WP-CLI 利用可能なホスティング確認

### 7.2 実施
- [ ] サーバーへ画像転送（rsync / SCP）
- [ ] WP-CLI で一括インポート実行
- [ ] メディアライブラリ件数確認（92件 + PDF 2件）
- [ ] 重複ファイル確認
- [ ] フォルダ分け（Enhanced Media Library 等）
- [ ] ACF フィールドへの紐付け（テンプレート上）

### 7.3 検証
- [ ] 各ページの画像表示確認（PC / SP）
- [ ] PDFダウンロードリンク動作確認
- [ ] 画像最適化プラグイン稼働確認（WebP配信）
- [ ] PageSpeed Insights 計測

### 7.4 仕上げ
- [ ] 全画像へ ALT テキスト設定
- [ ] 旧URL→新URL 置換（必要な場合）
- [ ] 不要な旧画像削除
- [ ] 本番バックアップ取得

---

## 8. ファイル一覧（現状参考）

### 8.1 トップページで使用される画像
- `grouphome-hero.jpg`（ヒーロー1枚目）
- `gh-room-private-01.jpg`（ヒーロー2枚目）
- `nishikamata-exterior-front.jpg`（ヒーロー3枚目）
- `gh-washroom-01.jpg`（ヒーロー4枚目）
- `houkan-bloodpressure-02.jpg`（ヒーロー5枚目）
- `gh-allowance-support.jpg`（ヒーロー6枚目）
- `secondsection.jpg`（philosophy 大）
- `gh-caregivers-02.jpg`（philosophy 左下）
- `gh-staff-group-04.jpg`（philosophy 右下）
- `logo-grouphome.png` / `logo-houkan.png`（サービスロゴ）

### 8.2 訪問看護ページで使用される画像
- `houkan-medicine-01.jpg`（メイン写真）
- ギャラリー6種：`houkan-medicine-01.jpg` `houkan-medicine-03.jpg` `houkan-bloodpressure.jpg` `houkan-staff-all-01.jpg` `meeting-allmember-03.jpg` `meeting-04.jpg`

### 8.3 グループホームページで使用される画像
- `omori-exterior-front.jpg`（メイン写真）
- 大森南：`grouphome-hero.jpg` / `omori-living-2f.jpg`
- 大岡山①：`okayama12-exterior-01.jpg` / `okayama1-living.jpg`
- 大岡山②：`gh-washroom-01.jpg` / `okayama2-room-01.jpg`
- 西蒲田：`nishikamata-exterior-front.jpg` / `nishikamata-kitchen-02.jpg`
- ギャラリー6種：`gh-cooking-02.jpg` `gh-meal-04.jpg` `gh-staff-group-03.jpg` `gh-allowance-support.jpg` `meeting-06.jpg` `gh-meal-01.jpg`

### 8.4 共通（ヘッダー・フッター）
- `logo-company.png`（会社ロゴ）

---

## 9. 移行スケジュール例

| 工程 | 所要 | 担当 |
|------|------|------|
| 画像棚卸し・命名整理 | 0.5日 | 制作会社 |
| サーバー転送・一括インポート | 0.5日 | 制作会社 |
| 重複・参照チェック | 0.5日 | 制作会社 |
| ALT テキスト一括設定 | 1日 | 発注元（リスト用意必要） |
| 動作確認・URL置換 | 0.5日 | 制作会社 |
| **合計** | **3日** | |

---

## 10. リスク・注意事項

- **大文字小文字を区別するサーバー：** ファイル名の大小不一致でリンク切れの可能性あり。WP-CLI 取り込み時に確認
- **EXIF 情報残存：** スマホ撮影画像は GPS 情報等が残ることあり。プライバシー観点で削除推奨（ImageMagick `mogrify -strip *.jpg`）
- **アップロード上限：** php.ini の `upload_max_filesize`・`post_max_size` を 32MB 程度に拡張要
- **メモリ上限：** 一括処理時に `memory_limit` 256M 以上推奨
- **同名ファイルの重複：** ACF Gallery / Image フィールドで参照される画像を変更する際、同名で上書きすると WP メディアライブラリは別IDで再登録する場合あり。**`Replace Media` プラグイン**で上書き運用推奨

---

## 関連ドキュメント

- `docs/wordpress-acf-spec.md` — ACF設計書
- `docs/acf-fields.json` — ACFインポートJSON
- `docs/site-operations-manual.md` — 運用マニュアル
- `docs/wordpress-rfp-template.md` — WP移行RFPテンプレート
