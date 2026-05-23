#!/usr/bin/env python3
"""
ブログ記事HTML一括生成スクリプト

ARTICLES → /blog/{slug}/index.html （即時公開）
SCHEDULED → /.scheduled/{date}/{slug}/index.html （月次で公開）

実行: python3 scripts/generate_blog.py
"""

import os
import re
import json
from pathlib import Path
from articles_data import ARTICLES, SCHEDULED, CATEGORIES, AUTHOR

ROOT = Path(__file__).resolve().parent.parent
BASE_URL = "https://www.suiseistudio.com"

# 関連記事ロジック: 同カテゴリの他記事を最大3件返す
def related_articles(current_slug, current_category, all_articles):
    related = []
    for a in all_articles:
        if a["slug"] == current_slug:
            continue
        if a["category"] == current_category:
            related.append(a)
        if len(related) >= 3:
            break
    # 足りない場合は他カテゴリから補完
    if len(related) < 3:
        for a in all_articles:
            if a["slug"] == current_slug or a in related:
                continue
            related.append(a)
            if len(related) >= 3:
                break
    return related[:3]

def article_html(article, depth, is_scheduled, all_articles):
    """
    depth: assetsへの相対パス階層数
      /blog/{slug}/index.html → depth=2 → ../../
      /.scheduled/{date}/{slug}/index.html → depth=3 → ../../../
    """
    up = "../" * depth
    slug = article["slug"]
    title = article["title"]
    desc = article["description"]
    image = article["image"]
    image_alt = article["image_alt"]
    body = article["body"]
    publish_date = article["publish_date"]
    category = article["category"]
    category_name = CATEGORIES[category]

    canonical = f"{BASE_URL}/blog/{slug}/"

    noindex_meta = ""
    if is_scheduled:
        noindex_meta = '<meta name="robots" content="noindex, nofollow">'

    # 関連記事HTML
    rel = related_articles(slug, category, all_articles)
    related_html = ""
    if rel:
        items = "\n".join(
            f'        <li><a href="../{r["slug"]}/">{r["title"]}</a></li>'
            for r in rel
        )
        related_html = f"""
    <div class="post-related">
      <p class="post-related__title">関連記事</p>
      <ul>
{items}
      </ul>
    </div>"""

    return f"""<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>{title} | SUISEI STUDIO</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{desc}">
{noindex_meta}
<link rel="canonical" href="{canonical}">
<link rel="alternate" hreflang="ja" href="{canonical}">
<link rel="alternate" hreflang="x-default" href="{canonical}">

<meta property="og:type" content="article">
<meta property="og:site_name" content="SUISEI STUDIO">
<meta property="og:title" content="{title}">
<meta property="og:description" content="{desc}">
<meta property="og:url" content="{canonical}">
<meta property="og:image" content="{image}">
<meta property="og:locale" content="ja_JP">
<meta property="article:published_time" content="{publish_date}">
<meta property="article:author" content="{AUTHOR['name']}">
<meta property="article:section" content="{category_name}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{title}">
<meta name="twitter:description" content="{desc}">
<meta name="twitter:image" content="{image}">

<link rel="preconnect" href="https://images.unsplash.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;700;800&family=Sora:wght@300;400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{up}assets/css/style.css">

<script type="application/ld+json">
{{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{title}",
  "description": "{desc}",
  "datePublished": "{publish_date}",
  "dateModified": "{publish_date}",
  "inLanguage": "ja",
  "image": "{image}",
  "author": {{
    "@type": "Person",
    "name": "{AUTHOR['name']}",
    "alternateName": "{AUTHOR['name_ja']}",
    "jobTitle": "{AUTHOR['role']}",
    "url": "{BASE_URL}/{AUTHOR['url_path']}",
    "image": "{BASE_URL}/{AUTHOR['photo']}",
    "worksFor": {{
      "@type": "Organization",
      "name": "SUISEI STUDIO",
      "url": "{BASE_URL}/"
    }}
  }},
  "publisher": {{
    "@type": "Organization",
    "name": "SUISEI STUDIO",
    "logo": {{ "@type": "ImageObject", "url": "{BASE_URL}/assets/img/logo_black.png" }}
  }},
  "mainEntityOfPage": "{canonical}",
  "articleSection": "{category_name}"
}}
</script>
<script type="application/ld+json">
{{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {{ "@type": "ListItem", "position": 1, "name": "ホーム", "item": "{BASE_URL}/" }},
    {{ "@type": "ListItem", "position": 2, "name": "ブログ", "item": "{BASE_URL}/blog/" }},
    {{ "@type": "ListItem", "position": 3, "name": "{title}", "item": "{canonical}" }}
  ]
}}
</script>
</head>
<body>

<div class="cursor"></div>
<div class="cursor-follower"></div>

<div class="orbs" aria-hidden="true">
  <div class="orb orb--1"></div>
  <div class="orb orb--2"></div>
  <div class="orb orb--3"></div>
</div>

<nav class="nav" id="nav">
  <a href="{up}" class="nav__logo" lang="en">SUISEI</a>
  <ul class="nav__menu" id="nav-menu">
    <li><a href="{up}#studio" class="nav__link" lang="en">STUDIO</a></li>
    <li><a href="{up}info/" class="nav__link" lang="en">NEWS</a></li>
    <li><a href="{up}blog/" class="nav__link nav__link--active" lang="en">BLOG</a></li>
    <li><a href="{up}riyou/" class="nav__link" lang="en">GUIDE</a></li>
    <li><a href="{up}#faq" class="nav__link" lang="en">FAQ</a></li>
  </ul>
  <div class="nav__actions">
    <a href="https://system.suiseistudio.com/" target="_blank" rel="noopener" class="nav__reserve" lang="en">RESERVE</a>
  </div>
  <button class="hamburger" id="hamburger" aria-label="メニュー">
    <span class="hamburger__line"></span>
    <span class="hamburger__line"></span>
    <span class="hamburger__line"></span>
  </button>
</nav>
<div class="mobile-menu" id="mobile-menu">
  <ul class="mobile-menu__list">
    <li><a href="{up}#studio" class="mobile-menu__link" lang="en">STUDIO</a></li>
    <li><a href="{up}info/" class="mobile-menu__link" lang="en">NEWS</a></li>
    <li><a href="{up}blog/" class="mobile-menu__link" lang="en">BLOG</a></li>
    <li><a href="{up}riyou/" class="mobile-menu__link" lang="en">GUIDE</a></li>
    <li><a href="{up}#faq" class="mobile-menu__link" lang="en">FAQ</a></li>
    <li><a href="https://system.suiseistudio.com/" target="_blank" rel="noopener" class="mobile-menu__link mobile-menu__link--reserve" lang="en">RESERVE</a></li>
  </ul>
</div>

<section class="sub-hero">
  <div class="sub-hero__grain" aria-hidden="true"></div>
  <div class="sub-hero__content">
    <span class="sub-hero__label" lang="en">BLOG</span>
    <p class="sub-hero__title">ブログ</p>
  </div>
</section>

<article class="article">
  <div class="article__inner">
    <div class="post-meta">
      <span class="post-meta__category">{category_name}</span>
      <time datetime="{publish_date}">{publish_date.replace('-', '.')}</time>
    </div>
    <h1 class="article__title">{title}</h1>
    <figure class="article__cover post-figure">
      <img src="{image}" alt="{image_alt}" loading="lazy" decoding="async">
    </figure>
    <div class="article__body post-body">
{body}
    </div>

    <div class="post-author">
      <div class="post-author__avatar">{AUTHOR['initials']}<img src="{up}{AUTHOR['photo']}" alt="{AUTHOR['name']}" loading="lazy" decoding="async" onerror="this.remove()"></div>
      <div>
        <div class="post-author__name"><a href="{up}{AUTHOR['url_path']}">{AUTHOR['name']}</a><span style="color:#888; font-weight:400; font-size:12px; margin-left:8px;">{AUTHOR['role']}</span></div>
        <div class="post-author__bio">{AUTHOR['bio']}</div>
      </div>
    </div>
{related_html}

    <a href="../" class="article__back" style="margin-top:32px;">&larr; ブログ一覧へ戻る</a>
  </div>
</article>

<footer class="page-footer">
  <div class="page-footer__inner">
    <div class="page-footer__nav">
      <a href="{up}" class="page-footer__logo" lang="en">SUISEI STUDIO</a>
      <ul class="page-footer__links">
        <li><a href="{up}#studio" lang="en">STUDIO</a></li>
        <li><a href="{up}info/" lang="en">NEWS</a></li>
        <li><a href="{up}blog/" lang="en">BLOG</a></li>
        <li><a href="{up}riyou/" lang="en">GUIDE</a></li>
        <li><a href="{up}#faq" lang="en">FAQ</a></li>
      </ul>
    </div>
    <div class="page-footer__info">
      <p>〒573-1197 大阪府枚方市中宮東之町6-15 Cometaビル</p>
      <a href="https://www.instagram.com/suiseidancestudio/" target="_blank" rel="noopener" lang="en">INSTAGRAM</a>
    </div>
    <p class="page-footer__copy" lang="en">&copy; SUISEI STUDIO</p>
  </div>
</footer>

<script src="{up}assets/js/main.js" defer></script>
</body>
</html>
"""

def write_article(article, target_path, depth, is_scheduled, all_articles):
    target_path.mkdir(parents=True, exist_ok=True)
    html = article_html(article, depth, is_scheduled, all_articles)
    (target_path / "index.html").write_text(html)

def card_html(article):
    """Blog index 用のカード HTML"""
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

def build_blog_index(published_articles):
    cards = "\n".join(card_html(a) for a in sorted(published_articles, key=lambda x: x["publish_date"], reverse=True))
    return f"""<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ブログ | SUISEI STUDIO（大阪・枚方のダンスレンタルスタジオ）</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="SUISEI STUDIOのブログ。枚方・京阪沿線のダンス練習情報、スタジオの選び方、ダンス上達のハウツーなどを発信しています。">
<link rel="canonical" href="{BASE_URL}/blog/">
<link rel="alternate" hreflang="ja" href="{BASE_URL}/blog/">
<link rel="alternate" hreflang="x-default" href="{BASE_URL}/blog/">

<meta property="og:type" content="website">
<meta property="og:site_name" content="SUISEI STUDIO">
<meta property="og:title" content="ブログ | SUISEI STUDIO">
<meta property="og:description" content="枚方・京阪沿線のダンス練習情報、スタジオの選び方、ダンス上達のハウツーを発信。">
<meta property="og:url" content="{BASE_URL}/blog/">
<meta property="og:image" content="{BASE_URL}/assets/img/top_bg.jpg">
<meta property="og:locale" content="ja_JP">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="ブログ | SUISEI STUDIO">
<meta name="twitter:description" content="枚方・京阪沿線のダンス練習情報、スタジオの選び方、ダンス上達のハウツー。">
<meta name="twitter:image" content="{BASE_URL}/assets/img/top_bg.jpg">

<link rel="preconnect" href="https://images.unsplash.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;700;800&family=Sora:wght@300;400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">

<script type="application/ld+json">
{{
  "@context": "https://schema.org",
  "@type": "Blog",
  "name": "SUISEI STUDIO Blog",
  "url": "{BASE_URL}/blog/",
  "description": "枚方・京阪沿線のダンス練習情報、スタジオの選び方、ダンス上達のハウツーを発信するブログ。",
  "publisher": {{
    "@type": "Organization",
    "name": "SUISEI STUDIO",
    "logo": {{ "@type": "ImageObject", "url": "{BASE_URL}/assets/img/logo_black.png" }}
  }}
}}
</script>
<script type="application/ld+json">
{{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {{ "@type": "ListItem", "position": 1, "name": "ホーム", "item": "{BASE_URL}/" }},
    {{ "@type": "ListItem", "position": 2, "name": "ブログ", "item": "{BASE_URL}/blog/" }}
  ]
}}
</script>
</head>
<body>

<div class="cursor"></div>
<div class="cursor-follower"></div>

<div class="orbs" aria-hidden="true">
  <div class="orb orb--1"></div>
  <div class="orb orb--2"></div>
  <div class="orb orb--3"></div>
</div>

<nav class="nav" id="nav">
  <a href="../" class="nav__logo" lang="en">SUISEI</a>
  <ul class="nav__menu" id="nav-menu">
    <li><a href="../#studio" class="nav__link" lang="en">STUDIO</a></li>
    <li><a href="../info/" class="nav__link" lang="en">NEWS</a></li>
    <li><a href="./" class="nav__link nav__link--active" lang="en">BLOG</a></li>
    <li><a href="../riyou/" class="nav__link" lang="en">GUIDE</a></li>
    <li><a href="../#faq" class="nav__link" lang="en">FAQ</a></li>
  </ul>
  <div class="nav__actions">
    <a href="https://system.suiseistudio.com/" target="_blank" rel="noopener" class="nav__reserve" lang="en">RESERVE</a>
  </div>
  <button class="hamburger" id="hamburger" aria-label="メニュー">
    <span class="hamburger__line"></span>
    <span class="hamburger__line"></span>
    <span class="hamburger__line"></span>
  </button>
</nav>
<div class="mobile-menu" id="mobile-menu">
  <ul class="mobile-menu__list">
    <li><a href="../#studio" class="mobile-menu__link" lang="en">STUDIO</a></li>
    <li><a href="../info/" class="mobile-menu__link" lang="en">NEWS</a></li>
    <li><a href="./" class="mobile-menu__link" lang="en">BLOG</a></li>
    <li><a href="../riyou/" class="mobile-menu__link" lang="en">GUIDE</a></li>
    <li><a href="../#faq" class="mobile-menu__link" lang="en">FAQ</a></li>
    <li><a href="https://system.suiseistudio.com/" target="_blank" rel="noopener" class="mobile-menu__link mobile-menu__link--reserve" lang="en">RESERVE</a></li>
  </ul>
</div>

<section class="sub-hero">
  <div class="sub-hero__grain" aria-hidden="true"></div>
  <div class="sub-hero__content">
    <span class="sub-hero__label" lang="en">BLOG</span>
    <h1 class="sub-hero__title">ブログ</h1>
  </div>
</section>

<section class="blog-page">
  <div class="blog-page__inner">
    <p style="color:#888; font-size:14px; line-height:1.8;">枚方・京阪沿線のダンス練習情報、スタジオの選び方、ダンス上達のハウツーなどを発信しています。</p>
    <div class="blog-grid">
{cards}
    </div>
  </div>
</section>

<footer class="page-footer">
  <div class="page-footer__inner">
    <div class="page-footer__nav">
      <a href="../" class="page-footer__logo" lang="en">SUISEI STUDIO</a>
      <ul class="page-footer__links">
        <li><a href="../#studio" lang="en">STUDIO</a></li>
        <li><a href="../info/" lang="en">NEWS</a></li>
        <li><a href="./" lang="en">BLOG</a></li>
        <li><a href="../riyou/" lang="en">GUIDE</a></li>
        <li><a href="../#faq" lang="en">FAQ</a></li>
      </ul>
    </div>
    <div class="page-footer__info">
      <p>〒573-1197 大阪府枚方市中宮東之町6-15 Cometaビル</p>
      <a href="https://www.instagram.com/suiseidancestudio/" target="_blank" rel="noopener" lang="en">INSTAGRAM</a>
    </div>
    <p class="page-footer__copy" lang="en">&copy; SUISEI STUDIO</p>
  </div>
</footer>

<script src="../assets/js/main.js" defer></script>
</body>
</html>
"""

def main():
    # 即時公開記事（/blog/{slug}/）
    blog_dir = ROOT / "blog"
    blog_dir.mkdir(exist_ok=True)
    for a in ARTICLES:
        target = blog_dir / a["slug"]
        write_article(a, target, depth=2, is_scheduled=False, all_articles=ARTICLES)
        print(f"WRITE: blog/{a['slug']}/index.html")

    # ブログ一覧
    (blog_dir / "index.html").write_text(build_blog_index(ARTICLES))
    print("WRITE: blog/index.html")

    # スケジュール記事（/.scheduled/{date}/{slug}/）
    sched_dir = ROOT / ".scheduled"
    sched_dir.mkdir(exist_ok=True)
    for a in SCHEDULED:
        target = sched_dir / a["publish_date"] / a["slug"]
        write_article(a, target, depth=3, is_scheduled=True, all_articles=ARTICLES + SCHEDULED)
        print(f"WRITE: .scheduled/{a['publish_date']}/{a['slug']}/index.html")

    # スケジュールマニフェスト（GitHub Action 用）
    manifest = {
        "scheduled": [
            {"date": a["publish_date"], "slug": a["slug"], "title": a["title"],
             "category": a["category"], "image": a["image"], "image_alt": a["image_alt"],
             "description": a["description"]}
            for a in SCHEDULED
        ]
    }
    (sched_dir / "manifest.json").write_text(json.dumps(manifest, ensure_ascii=False, indent=2))
    print("WRITE: .scheduled/manifest.json")

    print(f"\n--- Summary ---")
    print(f"Immediate articles: {len(ARTICLES)}")
    print(f"Scheduled articles: {len(SCHEDULED)}")
    print(f"Total: {len(ARTICLES) + len(SCHEDULED)}")

if __name__ == "__main__":
    main()
