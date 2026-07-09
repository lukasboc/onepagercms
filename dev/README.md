# dev/ — admin stylesheet build (developers only)

This directory rebuilds `css/admin.css`, the stylesheet of the admin backend
(Tailwind CSS 4 + daisyUI 5). The compiled file is **committed to the repo** —
end users installing or updating OnePagerCMS never run this build.

```bash
cd dev
npm install
npm run build    # or: npm run watch
```

The same `npm install` also provides the vendored admin assets
(`plugins/fontawesome-free/`, `plugins/jquery/`) — copy them from
`node_modules` if they ever need updating.

The utility safelist in `admin.src.css` is part of the public contract for
plugin admin pages. If you change it, update `docs/EXTENSIONS.md` accordingly.
