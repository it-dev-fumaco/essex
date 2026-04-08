{{-- Inlined for homepage only; avoids /css/css/... 404 behind reverse proxies. --}}
<style id="portal-home-styles">
:root {
    --portal-home-bg: #f3f5f8;
    --portal-card-radius: 14px;
    --portal-shadow: 0 4px 24px rgba(18, 38, 63, 0.06);
    --portal-gradient: linear-gradient(
        90deg,
        #1a5fb4 0%,
        #0f8f8a 55%,
        #159957 100%
    );
}

.portal-home {
    background: var(--portal-home-bg);
    min-height: 60vh;
}

.portal-home-main {
    padding-top: 0;
}

.portal-hero {
    background: var(--portal-gradient);
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    width: 100vw;
    max-width: 100vw;
    margin-top: 0;
    overflow: visible;
}

.portal-hero-title {
    font-family: 'Poppins-Bold', 'Poppins-Regular', sans-serif;
    font-size: clamp(1.45rem, 3.2vw, 1.85rem);
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.02em;
    margin: 0;
    line-height: 1.2;
}

.portal-hero-glass {
    position: relative;
    background: rgba(255, 255, 255, 0.22);
    border: 1px solid rgba(255, 255, 255, 0.45);
    border-radius: 16px;
    padding: 1rem 1.15rem;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

.portal-hero-search-wrap {
    position: relative;
    overflow: visible;
    width: 100%;
    z-index: 2;
}

@media (min-width: 992px) {
    .portal-hero-search-wrap {
        margin-left: auto;
    }
}

.portal-hero .input-group {
    flex-wrap: nowrap;
    align-items: stretch;
    gap: 0;
}

/* Icon segment: round only the outer-left corners; input must stay square on the left so it meets flush */
.portal-hero .input-group-text {
    border-radius: 12px 0 0 12px;
    border: none;
    padding-left: 1rem;
    padding-right: 0.75rem;
    color: #6c757d;
}

.portal-hero .carousel-search {
    font-family: 'Poppins-Regular', sans-serif !important;
    font-size: 1rem;
    border-radius: 0 !important;
    border: none;
    padding: 0.65rem 1rem;
    min-height: 48px;
    flex: 1 1 auto;
    min-width: 0;
}

.portal-hero .submit-search.portal-btn-gradient {
    border: none;
    border-radius: 0 12px 12px 0 !important;
    min-height: 48px;
    padding-left: 1.25rem;
    padding-right: 1.25rem;
    color: #fff;
    background: linear-gradient(90deg, #159957 0%, #0f8f8a 100%);
    font-weight: 700;
}

.portal-hero .submit-search.portal-btn-gradient:hover {
    filter: brightness(1.05);
    color: #fff;
}

/* Search suggestions: out of document flow so the page never reflows when open */
.portal-hero #autocomplete-container.portal-search-autocomplete {
    position: absolute;
    left: 0 !important;
    right: auto !important;
    top: 100% !important;
    margin-top: 6px;
    width: 100% !important;
    max-width: 100%;
    z-index: 9999 !important;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-height: min(70vh, 28rem);
    overflow-y: auto;
    overflow-x: hidden;
    box-sizing: border-box;
}

.portal-hero #autocomplete-container .portal-search-loading {
    min-height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.portal-card {
    border: none;
    border-radius: var(--portal-card-radius);
    box-shadow: var(--portal-shadow);
    background: #fff;
    overflow: hidden;
}

.portal-card .card-header {
    background: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    font-weight: 700;
    font-size: 1rem;
    padding: 0.85rem 1.1rem;
}

.portal-card .card-body {
    padding: 1rem 1.1rem;
}

.portal-systems-title {
    font-family: 'Poppins-Bold', sans-serif;
    font-size: 1.15rem;
    color: #1a1a2e;
    margin-bottom: 1rem;
}

.portal-system-card {
    border-radius: var(--portal-card-radius);
    border: 1px solid rgba(0, 0, 0, 0.06);
    box-shadow: var(--portal-shadow);
    height: 100%;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.portal-system-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(18, 38, 63, 0.1);
}

.portal-system-card .portal-system-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 0.5rem;
}

.portal-system-card .portal-system-icon.accent-0 {
    background: linear-gradient(135deg, #1a5fb4, #3d8bfd);
}
.portal-system-card .portal-system-icon.accent-1 {
    background: linear-gradient(135deg, #159957, #20c997);
}
.portal-system-card .portal-system-icon.accent-2 {
    background: linear-gradient(135deg, #0f8f8a, #2dd4bf);
}
.portal-system-card .portal-system-icon.accent-3 {
    background: linear-gradient(135deg, #5a67d8, #7c3aed);
}

.portal-system-name {
    font-weight: 700;
    font-size: 1rem;
    color: #1a1a2e;
    margin-bottom: 0.25rem;
}

.portal-system-url {
    font-size: 0.78rem;
    color: #6c757d;
    word-break: break-all;
    margin-bottom: 0.75rem;
}

.portal-btn-open {
    display: block;
    width: 100%;
    text-align: center;
    padding: 0.5rem 0.75rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.875rem;
    color: #fff !important;
    text-decoration: none !important;
    background: var(--portal-gradient);
    border: none;
}

.portal-btn-open:hover {
    filter: brightness(1.06);
    color: #fff !important;
}

.portal-vision-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    min-height: 220px;
    box-shadow: var(--portal-shadow);
}

.portal-vision-card .portal-vision-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
}

.portal-vision-card .portal-vision-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: rgba(0, 0, 0, 0.58);
}

.portal-vision-carousel {
    position: relative;
    z-index: 2;
    min-height: 220px;
}

.portal-vision-carousel .carousel-inner {
    min-height: 220px;
}

.portal-vision-carousel .carousel-item {
    min-height: 220px;
    transition: transform 0.5s ease-in-out;
}

.portal-vision-slide-inner {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    min-height: 220px;
    padding: 1.35rem 1.5rem 1.5rem;
    margin: 0 auto;
    max-width: 92%;
    color: #fff;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.45);
}

.portal-vision-heading {
    font-family: 'Poppins-Bold', 'Poppins-Regular', sans-serif;
    font-size: clamp(1.35rem, 3vw, 1.85rem);
    font-weight: 700;
    margin-bottom: 0.65rem;
    color: #fff;
}

.portal-vision-slide-inner p {
    font-size: clamp(0.9rem, 1.8vw, 1.05rem);
    line-height: 1.45;
    margin-bottom: 0.65rem;
    max-width: 36rem;
    color: #fff;
}

.portal-vision-carousel .portal-vision-carousel-btn {
    width: 14%;
    opacity: 1;
    border: none;
    background: transparent;
    z-index: 3;
}

.portal-vision-carousel .portal-vision-carousel-btn i {
    font-size: 1.45rem;
    color: #fff;
    opacity: 0.92;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.6));
}

.portal-vision-carousel .portal-vision-carousel-btn:hover i {
    opacity: 1;
}

.portal-vision-carousel .carousel-control-prev-icon,
.portal-vision-carousel .carousel-control-next-icon {
    display: none;
}

/* Systems + Vision share one row on xl: 5:4 of the nine columns — heights match */
@media (min-width: 1200px) {
    .portal-home > .container-fluid > .row > .col-xl-9 > .row.align-items-xl-start {
        flex-wrap: nowrap;
    }

    .portal-home-systems-split.col-12 {
        flex: 0 0 55.555555% !important;
        max-width: 55.555555% !important;
        width: 55.555555% !important;
    }

    .portal-home-vision-split.col-12 {
        flex: 0 0 44.444444% !important;
        max-width: 44.444444% !important;
        width: 44.444444% !important;
    }

    .portal-home-vision-split .portal-vision-card {
        min-height: 0;
        height: auto;
        width: 100%;
        max-height: min(380px, 48vh);
    }

    .portal-home-vision-split .portal-vision-carousel,
    .portal-home-vision-split .portal-vision-carousel .carousel-inner {
        min-height: 0;
    }

    .portal-home-vision-split .portal-vision-carousel .carousel-inner {
        flex: 1 1 auto;
    }

    .portal-home-vision-split .portal-vision-carousel .carousel-item {
        min-height: 100%;
    }

    .portal-home-vision-split .portal-vision-slide-inner {
        min-height: 0;
        padding: 1.1rem 1rem 1.25rem;
    }

    .portal-home-vision-split .portal-vision-heading {
        font-size: clamp(1.35rem, 1.9vw, 1.75rem);
    }

    .portal-home-vision-split .portal-vision-slide-inner p {
        font-size: clamp(0.85rem, 1.1vw, 0.98rem);
    }

    /* Optional: legacy class if reused elsewhere */
    .portal-vision-card--max {
        min-height: min(620px, 62vh);
    }

    .portal-vision-card--max .portal-vision-carousel,
    .portal-vision-card--max .portal-vision-carousel .carousel-inner,
    .portal-vision-card--max .portal-vision-carousel .carousel-item {
        min-height: min(620px, 62vh);
    }

    .portal-vision-card--max .portal-vision-slide-inner {
        min-height: min(620px, 62vh);
        padding: 2.25rem 1.75rem 2.75rem;
    }

    .portal-vision-card--max .portal-vision-heading {
        font-size: clamp(2rem, 2.8vw, 2.6rem);
    }

    .portal-vision-card--max .portal-vision-slide-inner p {
        font-size: clamp(1.05rem, 1.5vw, 1.3rem);
    }
}

@media (max-width: 991.98px) {
    .portal-hero-glass {
        padding: 0.65rem 0.85rem;
        border-radius: 12px;
    }

    .portal-hero .carousel-search,
    .portal-hero .submit-search.portal-btn-gradient {
        min-height: 44px;
    }

    .portal-hero .carousel-search {
        font-size: 0.9375rem;
    }
}

@media (max-width: 575.98px) {
    .portal-hero-title {
        font-size: 1.35rem;
    }

    .portal-vision-carousel,
    .portal-vision-carousel .carousel-inner,
    .portal-vision-carousel .carousel-item {
        min-height: 200px;
    }

    .portal-vision-slide-inner {
        min-height: 200px;
        padding: 1.1rem 0.85rem 1.25rem;
    }

    .portal-vision-carousel .portal-vision-carousel-btn {
        width: 11%;
    }

    .portal-vision-carousel .portal-vision-carousel-btn i {
        font-size: 1.2rem;
    }
}

.portal-reminders .form-check-label {
    color: #3d4450;
}

.portal-reminders .form-check-input {
    margin-top: 0.35em;
}

.portal-widget-time {
    border-top: 3px solid #11703c;
}

/* Helpful Articles (was injected via <style> in AJAX fragment — keep rules here to avoid cascade/stacking glitches) */
.portal-tbl-manuals {
    position: relative;
    z-index: 1;
}

.portal-helpful-articles .portal-article-list {
    padding: 0.5rem 0;
}

.portal-helpful-articles .portal-article-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 1.1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    transition: background 0.15s ease;
}

.portal-helpful-articles .portal-article-item:last-child {
    border-bottom: none;
}

.portal-helpful-articles .portal-article-item:hover {
    background: rgba(26, 95, 180, 0.04);
}

.portal-helpful-articles .portal-article-icon {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    color: #fff;
}

.portal-helpful-articles .portal-article-icon--blue {
    background: linear-gradient(135deg, #1a5fb4, #3d8bfd);
}

.portal-helpful-articles .portal-article-icon--green {
    background: linear-gradient(135deg, #159957, #20c997);
}

.portal-helpful-articles .portal-article-icon--teal {
    background: linear-gradient(135deg, #0f8f8a, #2dd4bf);
}

.portal-helpful-articles .portal-article-icon--muted {
    background: linear-gradient(135deg, #64748b, #94a3b8);
}

.portal-helpful-articles .portal-article-link {
    color: #1a1a2e;
    font-size: 0.9rem;
    text-decoration: none;
}

.portal-helpful-articles .portal-article-link:hover {
    color: #1a5fb4;
}
</style>
