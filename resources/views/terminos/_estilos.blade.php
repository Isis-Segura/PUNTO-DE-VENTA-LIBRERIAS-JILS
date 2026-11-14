<style>
    /* Paleta JILS (igual que pos-theme.css). Oscuro: clase .dark-mode o preferencia del sistema */
    :root {
      --t-page:#f3f0ec; --t-surface:#f5f3ef; --t-surface2:#ebe7e1; --t-border:#c9c1b9; --t-divider:#e6e0d9;
      --t-title:#1d2d2c; --t-body:#3a4c5d; --t-muted:#5b6b6a;
      --t-accent:#2a6d6c; --t-accent-hover:#1d2d2c; --t-reject:#5b6b6a; --t-reject-hover:#3a4c5d;
      --t-info-bg:#e6ebf0; --t-info-bd:#c5cfd8; --t-info-tx:#3a4c5d;
      --t-bad-bg:#f8e7e7; --t-bad-bd:#e3b9b9; --t-bad-tx:#7a2b2b;
      --t-ok-bg:#e3efe8; --t-ok-bd:#b9d4c4; --t-ok-tx:#1f4d38;
    }
    html.dark-mode {
      --t-page:#1f2a33; --t-surface:#3a4c5d; --t-surface2:#33434f; --t-border:#4a5f72; --t-divider:#44586b;
      --t-title:#ffffff; --t-body:#e1e7ec; --t-muted:#d0d8df;
      --t-accent:#2a6d6c; --t-accent-hover:#3a8a88; --t-reject:#4a6076; --t-reject-hover:#5a7087;
      --t-info-bg:#2b3a48; --t-info-bd:#5a7087; --t-info-tx:#e1e7ec;
      --t-bad-bg:rgba(229,138,138,.14); --t-bad-bd:rgba(229,138,138,.45); --t-bad-tx:#f7bcbc;
      --t-ok-bg:rgba(127,196,160,.14); --t-ok-bd:rgba(127,196,160,.45); --t-ok-tx:#a6dcc0;
    }
    @media (prefers-color-scheme: dark) {
      html:not(.dark-mode) {
        --t-page:#1f2a33; --t-surface:#3a4c5d; --t-surface2:#33434f; --t-border:#4a5f72; --t-divider:#44586b;
        --t-title:#ffffff; --t-body:#e1e7ec; --t-muted:#d0d8df;
        --t-accent:#2a6d6c; --t-accent-hover:#3a8a88; --t-reject:#4a6076; --t-reject-hover:#5a7087;
        --t-info-bg:#2b3a48; --t-info-bd:#5a7087; --t-info-tx:#e1e7ec;
        --t-bad-bg:rgba(229,138,138,.14); --t-bad-bd:rgba(229,138,138,.45); --t-bad-tx:#f7bcbc;
        --t-ok-bg:rgba(127,196,160,.14); --t-ok-bd:rgba(127,196,160,.45); --t-ok-tx:#a6dcc0;
      }
    }

    * {
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      margin: 0;
      padding: 24px 12px;
      display: grid;
      place-items: center;
      background: var(--t-page);
      color: var(--t-title);
      font-family: "Segoe UI", Arial, sans-serif;
    }

    .wrap {
      width: min(100%, 640px);
    }

    .card {
      overflow: hidden;
      background: var(--t-surface);
      border: 1px solid var(--t-border);
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(29, 45, 44, .08);
    }

    .card-head {
      padding: 20px 28px;
      background: #3b5c59;
      border-bottom: 4px solid #2a6d6c;
      color: #fff;
    }

    .card-head small {
      display: block;
      font-size: 13px;
      letter-spacing: .06em;
      opacity: .9;
      text-transform: uppercase;
    }

    .card-head h1 {
      margin: 4px 0 0;
      font-size: 22px;
    }

    .card-body {
      padding: 28px;
      color: var(--t-body);
      font-size: 15px;
      line-height: 1.6;
    }

    .card-body + .card-body {
      border-top: 1px solid var(--t-divider);
      background: var(--t-surface2);
    }

    .terminos h2 {
      margin: 0 0 12px;
      color: var(--t-title);
      font-size: 18px;
    }

    .terminos p {
      margin: 0 0 12px;
    }

    .terminos ul {
      margin: 0 0 20px;
      padding-left: 22px;
    }

    .terminos li + li {
      margin-top: 6px;
    }

    .decision {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    .decision form {
      display: contents;
    }

    .decision button {
      flex: 1 1 180px;
      min-height: 48px;
      padding: 12px 18px;
      border: 2px solid transparent;
      border-radius: 8px;
      color: #fff;
      cursor: pointer;
      font: inherit;
      font-weight: 700;
      transition: background-color .2s ease, transform .2s ease;
    }

    .decision button:hover {
      transform: translateY(-1px);
    }

    .decision button:focus-visible {
      outline: 3px solid var(--t-title);
      outline-offset: 3px;
    }

    .decision button:disabled {
      cursor: wait;
      opacity: .7;
      transform: none;
    }

    #accept {
      background: var(--t-accent);
    }

    #accept:hover {
      background: var(--t-accent-hover);
    }

    #reject {
      background: var(--t-reject);
    }

    #reject:hover,
    #reject[aria-pressed="true"] {
      background: var(--t-reject-hover);
    }

    #status,
    .status {
      min-height: 48px;
      margin: 18px 0 0;
      padding: 12px 14px;
      border: 1px solid var(--t-info-bd);
      border-radius: 8px;
      background: var(--t-info-bg);
      color: var(--t-info-tx);
    }

    #status[data-choice="rejected"] {
      border-color: var(--t-bad-bd);
      background: var(--t-bad-bg);
      color: var(--t-bad-tx);
    }

    .status.ok {
      margin: 0 0 20px;
      border-color: var(--t-ok-bd);
      background: var(--t-ok-bg);
      color: var(--t-ok-tx);
    }

    .section-title {
      margin: 0 0 12px;
      color: var(--t-title);
      font-size: 18px;
    }

    .creds {
      margin: 0 0 16px;
      border: 1px solid var(--t-divider);
      border-radius: 10px;
      background: var(--t-surface2);
    }

    .creds .row {
      padding: 14px 16px;
    }

    .creds .row + .row {
      border-top: 1px solid var(--t-divider);
    }

    .creds .lbl {
      display: block;
      color: var(--t-muted);
      font-size: 12px;
      text-transform: uppercase;
    }

    .creds .val {
      display: block;
      color: var(--t-title);
      font-size: 16px;
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    .creds .mono {
      font-family: Consolas, monospace;
    }

    .creds .muted {
      color: var(--t-body);
      font-size: 14px;
      font-weight: 400;
    }

    .hint {
      margin: 0 0 16px;
      color: var(--t-body);
      font-size: 14px;
    }

    .btn-login {
      display: block;
      padding: 12px 18px;
      border-radius: 8px;
      background: var(--t-accent);
      color: #fff;
      font-weight: 700;
      text-align: center;
      text-decoration: none;
    }

    .btn-login:hover {
      background: var(--t-accent-hover);
    }

    [hidden] {
      display: none !important;
    }

    .closing {
      text-align: center;
    }

    .closing h2 {
      margin: 0 0 8px;
      color: var(--t-title);
      font-size: 18px;
    }

    .closing p {
      margin: 0;
    }

    .spinner {
      width: 40px;
      height: 40px;
      margin: 8px auto 18px;
      border: 4px solid var(--t-info-bd);
      border-top-color: var(--t-accent);
      border-radius: 50%;
      animation: spin .9s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .spinner {
        animation: none;
      }
    }

    .foot {
      margin: 14px auto 0;
      color: var(--t-muted);
      font-size: 12px;
      text-align: center;
    }

    @media (max-width: 480px) {
      .card-head,
      .card-body {
        padding: 22px 20px;
      }
    }

    .btn-solicitar {
      display: block;
      width: 100%;
      min-height: 48px;
      padding: 12px 18px;
      border: 0;
      border-radius: 8px;
      background: var(--t-accent);
      color: #fff;
      cursor: pointer;
      font: inherit;
      font-weight: 700;
    }
    .btn-solicitar:hover { background: var(--t-accent-hover); }
    .btn-solicitar:disabled { cursor: wait; opacity: .7; }
    .unavailable { text-align: center; }
    .unavailable h2 { margin: 0 0 8px; color: var(--t-title); font-size: 20px; }
    .unavailable p { margin: 0 0 16px; }
</style>
