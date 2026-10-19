# Developer Portfolio

A high-performance, accessible developer portfolio built from the ground up with **Vue 3** and modern web standards. Designed with a strict performance-first mindset, this application showcases advanced front-end efficiency, responsive architecture, and inclusive UX design without relying on generic templates.

## Key Features

- **Framework Core:** Built with Vue 3 as a culmination of modern front-end engineering, performance tuning, and clean component structure.

- **WCAG 2.1 A & AA Compliant:** Verified through automated testing and manual audits using native screen readers (NVDA / VoiceOver).

- **Accessibility Control Panel:** Manages visual adaptations (contrast modes, reduced motion, reduced transparency, reading mask, dynamic typography) with conflict-free CSS architecture.

- **Non-Visual Alert System:** Dynamic ARIA live regions announce real-time status updates seamlessly without disrupting user focus.

- **Image Optimization Pipeline:** HTML5 `<picture>` tags with multi-format fallbacks (`AVIF` → `WebP` → `PNG`), responsive sourcing, and a high-DPI preview modal.

- **Internationalization (PL/EN):** Automatic locale detection with persistent state across sessions.

- **Cross-Engine Compatibility:** Validated across Chromium, Firefox, and WebKit/Safari, with terminal browser fallback support (`Lynx`, `w3m`) via semantic `<noscript>` structures.

## Getting Started

> [!WARNING]
> **Important Note for Recruiters & Reviewers:**
> This repository is meant for code review and architecture inspection rather than a 5-minute quick launch.
> 
> Running the project locally requires a combined setup of Vite (Vue 3 frontend) and a PHP backend server running concurrently in the `/public/` directory (handling dynamic localization, IP geolocation, and JSON endpoints). Additionally, media assets and content files have been omitted due to copyright restrictions.
> 
> **To see the live, fully populated application with complete styling, content, and assets, please visit the Website URL listed in the About section of this repository.**

### Prerequisites

- **Node.js** (v18+ recommended)
- **PHP** (v7.4+ or v8.x) for local backend endpoints

### Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/opaczynski/developer-portfolio.git](https://github.com/opaczynski/developer-portfolio.git)
   cd developer-portfolio
