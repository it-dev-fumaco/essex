{{-- Inlined so styles load without a separate /css/... request (avoids nginx static 404s). --}}
<style id="portal-theme-styles">
:root {
    --portal-gradient-start: #1a5fb4;
    --portal-gradient-mid: #0f8f8a;
    --portal-gradient-end: #159957;
    --portal-header-glass: rgba(255, 255, 255, 0.88);
}

.header.portal-header-modern .navbar {
    margin-bottom: 0;
    background: linear-gradient(
        90deg,
        rgba(230, 245, 252, 0.97) 0%,
        rgba(232, 248, 244, 0.98) 100%
    );
    font-size: 0.95rem;
    border: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 0;
    padding-top: 0.35rem;
    padding-bottom: 0.35rem;
}

.header.portal-header-modern .navbar-nav .nav-link {
    color: #1a1a2e !important;
    font-weight: 600;
    letter-spacing: 0.02em;
    padding: 0.5rem 0.65rem !important;
    border-radius: 0.5rem;
    text-transform: none;
}

.header.portal-header-modern .navbar-nav .nav-link:hover {
    color: var(--portal-gradient-start) !important;
    background: rgba(26, 95, 180, 0.06);
}

.header.portal-header-modern .navbar-nav .nav-link.active {
    color: var(--portal-gradient-start) !important;
    box-shadow: inset 0 -3px 0 0 var(--portal-gradient-end);
    background: transparent;
}

.header.portal-header-modern .navbar-nav .dropdown-toggle::after {
    vertical-align: 0.15em;
}

.portal-nav-brand-text {
    font-family: 'Poppins-Bold', 'Poppins-Regular', sans-serif;
    font-size: 1.05rem;
    line-height: 1.2;
    color: #1a1a2e;
}

.portal-nav-brand-sub {
    font-size: 0.65rem;
    color: #5c6370;
    font-weight: 500;
}

.portal-nav-essex-logo {
    display: block;
    max-height: 44px;
    width: auto;
    object-fit: contain;
}

.portal-nav-fumaco-logo {
    display: block;
    max-height: 20px;
    width: auto;
    object-fit: contain;
}

/* Welcome / account control lives in navbar (top gradient bar removed) */
.header.portal-header-modern .navbar .portal-nav-account .account-menu-toggle {
    color: #1a1a2e !important;
    background-color: rgba(17, 112, 60, 0.1) !important;
    border: 1px solid rgba(17, 112, 60, 0.22) !important;
    box-shadow: none;
}

.header.portal-header-modern .navbar .portal-nav-account .account-menu-toggle:hover,
.header.portal-header-modern .navbar .portal-nav-account .account-menu-toggle:focus {
    color: #1a1a2e !important;
    background-color: rgba(17, 112, 60, 0.16) !important;
    border-color: rgba(17, 112, 60, 0.3) !important;
}

.header.portal-header-modern .navbar .portal-nav-account .account-welcome {
    color: #1a1a2e !important;
    text-shadow: none !important;
    flex: 0 1 auto;
    min-width: 0;
    text-align: left;
}

.header.portal-header-modern .navbar .portal-nav-account .account-gear-icon {
    background-color: rgba(17, 112, 60, 0.15) !important;
    color: #11703c !important;
}

/* Navbar: logo + collapse grows; inside collapse = justify-between (main nav | account) */
.portal-navbar-inner {
    row-gap: 0.25rem;
    max-width: 100%;
    box-sizing: border-box;
}

.portal-navbar-toolbar {
    min-height: 0;
}

@media (min-width: 992px) {
    .portal-navbar-inner {
        flex-wrap: nowrap !important;
    }

    .portal-navbar-collapse.portal-navbar-collapse {
        align-items: center !important;
    }

    .portal-navbar-toolbar {
        flex-wrap: nowrap !important;
        justify-content: space-between !important;
        align-items: center !important;
        min-width: 0;
        max-width: 100%;
    }

    /* Main nav keeps natural width; empty flex area must not steal clicks from links */
    .portal-navbar-main {
        flex-wrap: nowrap !important;
        flex: 0 0 auto;
        min-width: max-content;
        gap: 0.25rem;
    }

    .portal-navbar-main .nav-item {
        margin: 0;
        flex-shrink: 0;
    }

    .header.portal-header-modern .portal-navbar-main .nav-link {
        white-space: nowrap;
        padding-left: 0.65rem !important;
        padding-right: 0.65rem !important;
    }

    .portal-nav-account-outer {
        flex: 1 1 0% !important;
        justify-content: flex-end !important;
        min-width: 0;
        max-width: 100%;
        pointer-events: none;
    }

    .portal-nav-account-outer .portal-nav-account {
        pointer-events: auto;
    }

    .portal-nav-account {
        width: auto !important;
        max-width: 100%;
        min-width: 0;
    }

    .portal-nav-account .account-setting {
        width: auto;
        max-width: 100%;
        min-width: 0;
    }

    /* Welcome control: pill hugs text + controls; long names ellipsis inside natural max width */
    .header.portal-header-modern .navbar .portal-nav-account .account-menu-toggle {
        width: auto;
        max-width: min(100%, 42rem);
        min-width: 0;
        display: inline-flex !important;
        align-items: center;
        justify-content: flex-start;
        gap: 0.5rem 0.75rem;
        box-sizing: border-box;
    }

    .header.portal-header-modern .navbar .portal-nav-account .account-gear-icon {
        margin-left: 0;
        flex-shrink: 0;
    }

    .header.portal-header-modern .navbar .portal-nav-account .account-menu-toggle::after {
        margin-left: 0;
        flex-shrink: 0;
    }

    .header.portal-header-modern .navbar .portal-nav-account .account-welcome {
        max-width: none !important;
        flex: 0 1 auto;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}

@media (max-width: 991.98px) {
    .portal-navbar-collapse {
        flex-basis: 100%;
        flex-grow: 1;
        align-items: stretch;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }

    .portal-navbar-collapse .navbar-nav,
    .portal-nav-account-outer {
        width: 100%;
    }

    .header.portal-header-modern .navbar-nav .nav-link.active {
        box-shadow: none !important;
        border-left: 3px solid var(--portal-gradient-end);
        padding-left: 0.75rem !important;
        border-radius: 0.35rem;
    }

    .portal-nav-account .account-menu-toggle {
        width: 100%;
        justify-content: space-between;
    }

    .portal-nav-account .account-welcome {
        font-size: 0.875rem !important;
        max-width: none !important;
        white-space: normal;
        line-height: 1.3;
    }

    .portal-nav-account .dropdown-menu {
        width: 100%;
    }
}

@media (max-width: 575.98px) {
    .portal-nav-essex-logo {
        max-height: 36px !important;
    }

    .portal-nav-fumaco-logo {
        max-height: 22px !important;
    }

    .portal-nav-brand-sub {
        font-size: 0.7rem !important;
    }
}

</style>
