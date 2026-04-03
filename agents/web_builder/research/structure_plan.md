# Structure Plan: chillna.co.jp Reproduction

## File Structure
```
webtester/
├── index.html                  # Main single-page website
├── assets/
│   ├── css/
│   │   └── style.css          # All styles (reset, layout, components, responsive)
│   ├── js/
│   │   └── main.js            # Interactions (scroll, menu, animations)
│   └── images/                 # Placeholder for images
└── agents/
    └── web_builder/
        ├── orchestrator/
        │   └── run.md          # Pipeline instructions
        └── research/
            ├── site_analysis.md    # Site content & design analysis
            └── structure_plan.md   # This file
```

## Sections Implemented
1. Header (fixed, responsive with hamburger)
2. Hero (full viewport, gradient, catchcopy)
3. About (concept explanation, vision/mission/value)
4. Service (6 service cards grid, area tags)
5. Staff (number counter, quote)
6. Company (info table, map embed)
7. Blog (3 article cards)
8. Recruit (CTA with gradient background)
9. Contact (email + phone methods)
10. Footer (brand, nav, SNS, copyright)

## Design Decisions
- Single-page layout with smooth scroll navigation
- Color palette: teal (#5BA49B), warm beige (#F9F6F2), coral (#E8967D)
- Typography: Montserrat (EN), Noto Serif JP (headings), Noto Sans JP (body)
- CSS-only animations with IntersectionObserver for scroll triggers
- Mobile-first responsive with breakpoints at 768px and 1024px
- SVG icons inline for performance
- No external dependencies besides Google Fonts
