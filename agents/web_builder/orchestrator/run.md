# Web Builder Pipeline - Orchestrator

## Overview
This pipeline builds a high-fidelity reproduction of a reference website through a series of agent-driven steps.

## Pipeline Steps

### Step 1: Research & Analysis
- Fetch and analyze the reference site structure
- Document all sections, content, colors, typography
- Output: `agents/web_builder/research/site_analysis.md`

### Step 2: Structure Planning
- Define HTML structure based on analysis
- Plan component hierarchy and sections
- Output: `agents/web_builder/research/structure_plan.md`

### Step 3: HTML Construction
- Build the full HTML document
- Implement all sections identified in research
- Output: `index.html`

### Step 4: CSS Styling
- Implement all visual styles
- Match colors, typography, spacing from reference
- Output: `assets/css/style.css`

### Step 5: Responsive & Animation
- Add responsive breakpoints
- Implement scroll animations and transitions
- Output: Updates to CSS and JS files

### Step 6: JavaScript Interactions
- Hamburger menu, smooth scroll
- Scroll-triggered animations
- Output: `assets/js/main.js`

### Step 7: Review & Deploy
- Final quality check
- Commit and push to branch

## Execution Notes
- Each agent should work in small increments to avoid timeouts
- Save progress as markdown documents between steps
- Prioritize visual fidelity to the reference site
