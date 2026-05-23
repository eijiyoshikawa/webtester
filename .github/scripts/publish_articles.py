#!/usr/bin/env python3
"""
スケジュール記事公開スクリプト（GitHub Action から呼び出される）

引数: 公開対象日 (YYYY-MM-DD)

処理:
1. .scheduled/{date}/ 配下の各記事フォルダを /blog/ に移動
2. 各記事 HTML から noindex meta を削除
3. /blog/index.html を再生成（公開済み記事を全部リスト）
4. /sitemap.xml に新規記事の URL を追加
"""

import sys
import re
import json
import shutil
import datetime
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent.parent
BASE_URL = "https://www.suiseistudio.com"
CATEGORIES = {"A": "ローカル", "B": "ハウツー", "C": "イベント", "D": "比較・選び方"}

def remove_noindex(html_path):
    html = html_path.read_text()
    html = re.sub(r'\s*<meta name="robots" content="noindex, nofollow">\n', '\n', html)
    html_path.write_text(html)

def fix_asset_paths(html_path):
    """スケジュール記事は depth=3 (../../../) で生成されているが、
    /blog/{slug}/ に移動すると depth=2 (../../) になるためパスを修正"""
    html = html_path.read_text()
    # ../../../ → ../../  (assets / ホームページ / 他ディレクトリ)
    html = html.replace('"../../../', '"../../')
    # ただし関連記事の ../{slug}/ は depth が変わらないので注意
    # 関連記事は ../slug/ なので元から depth に依存しない
    html_path.write_text(html)

def collect_published_articles():
    """blog/ 配下の各記事から情報を抜き出してリスト化"""
    blog_dir = ROOT / "blog"
    articles = []
    for child in blog_dir.iterdir():
        if not child.is_dir():
            continue
        idx = child / "index.html"
        if not idx.exists():
            continue
        html = idx.read_text()

        # メタデータを抜き出す
        title_m = re.search(r'<h1 class="article__title">([^<]+)</h1>', html)
        desc_m = re.search(r'<meta name="description" content="([^"]+)"', html)
        img_m = re.search(r'<meta property="og:image" content="([^"]+)"', html)
        date_m = re.search(r'<time datetime="(\d{4}-\d{2}-\d{2})"', html)
        cat_m = re.search(r'<span class="post-meta__category">([^<]+)</span>', html)
        alt_m = re.search(r'<figure class="article__cover post-figure">\s*<img src="[^"]+" alt="([^"]+)"', html, re.S)

        if not (title_m and date_m):
            continue

        # カテゴリ名 → カテゴリキー逆引き
        cat_name = cat_m.group(1) if cat_m else ""
        cat_key = next((k for k, v in CATEGORIES.items() if v == cat_name), "A")

        articles.append({
            "slug": child.name,
            "title": title_m.group(1),
            "description": desc_m.group(1) if desc_m else "",
            "image": img_m.group(1) if img_m else "",
            "image_alt": alt_m.group(1) if alt_m else "",
            "publish_date": date_m.group(1),
            "category": cat_key,
        })
    return articles

def card_html(article):
    return f"""    <a href="./{article['slug']}/" class="blog-card" data-category="{article['category']}">
      <div class="blog-card__img">
        <img src="{article['image']}" alt="{article['image_alt']}" loading="lazy" decoding="async">
      </div>
      <div class="blog-card__body">
        <div class="blog-card__meta">
          <span class="blog-card__category">{CATEGORIES[article['category']]}</span>
          <time datetime="{article['publish_date']}">{article['publish_date'].replace('-', '.')}</time>
        </div>
        <h2 class="blog-card__title">{article['title']}</h2>
        <p class="blog-card__excerpt">{article['description']}</p>
      </div>
    </a>"""

def rebuild_blog_index(articles):
    cards = "\n".join(card_html(a) for a in sorted(articles, key=lambda x: x["publish_date"], reverse=True))
    idx_path = ROOT / "blog" / "index.html"
    html = idx_path.read_text()
    # <div class="blog-grid"> ... </div> の中身を差し替え
    new_html = re.sub(
        r'(<div class="blog-grid">\s*\n).*?(\n\s*</div>)',
        lambda m: m.group(1) + cards + m.group(2),
        html, flags=re.S, count=1
    )
    idx_path.write_text(new_html)

def add_to_sitemap(new_articles):
    sm_path = ROOT / "sitemap.xml"
    if not sm_path.exists():
        return
    xml = sm_path.read_text()
    urls = ""
    for a in new_articles:
        loc = f"{BASE_URL}/blog/{a['slug']}/"
        urls += f"""  <url>
    <loc>{loc}</loc>
    <lastmod>{a['publish_date']}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
    <xhtml:link rel="alternate" hreflang="ja" href="{loc}"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="{loc}"/>
  </url>
"""
    # </urlset> の直前に追加
    xml = xml.replace("</urlset>", urls + "</urlset>")
    sm_path.write_text(xml)

def main():
    if len(sys.argv) < 2:
        target_date = datetime.datetime.utcnow().strftime("%Y-%m-%d")
    else:
        target_date = sys.argv[1]

    sched_dir = ROOT / ".scheduled" / target_date
    if not sched_dir.exists():
        print(f"No scheduled articles for {target_date}")
        return

    blog_dir = ROOT / "blog"
    new_articles = []

    for slug_dir in sched_dir.iterdir():
        if not slug_dir.is_dir():
            continue
        slug = slug_dir.name
        dest = blog_dir / slug
        if dest.exists():
            print(f"WARN: {dest} already exists, skipping")
            continue

        shutil.move(str(slug_dir), str(dest))
        idx = dest / "index.html"
        remove_noindex(idx)
        fix_asset_paths(idx)
        print(f"PUBLISHED: blog/{slug}/")
        new_articles.append({"slug": slug, "publish_date": target_date})

    # 空になったスケジュールフォルダを削除
    try:
        if not any(sched_dir.iterdir()):
            sched_dir.rmdir()
    except Exception:
        pass

    # 全ブログ記事を再収集して index を再構築
    all_articles = collect_published_articles()
    rebuild_blog_index(all_articles)
    print(f"REBUILT: blog/index.html ({len(all_articles)} articles)")

    # sitemap に追加（個別記事だけ）
    sitemap_articles = [a for a in all_articles if a["slug"] in {x["slug"] for x in new_articles}]
    if sitemap_articles:
        add_to_sitemap(sitemap_articles)
        print(f"UPDATED: sitemap.xml (+{len(sitemap_articles)} urls)")

if __name__ == "__main__":
    main()
