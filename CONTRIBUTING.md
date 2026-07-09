# Contribution
We are happy you want to help us with the project! You can contribute in following ways:
## Search for bugs
No project is bug-free. If you find a bug, please create an issue on our GitHub page and describe the problem.
## Make suggestions
If you think something could be solved better than we did - create an issue with your suggestion.
## Buy me a coffee
If you'd like to support the project financially, you can buy me a coffee.## Working on the admin backend design
The admin backend (since 1.2.0) is styled with Tailwind CSS 4 + daisyUI 5. The
compiled stylesheet `css/admin.css` is committed, so end users never need Node
or any build step. If you change admin markup or `dev/admin.src.css`, rebuild
the stylesheet and commit it:

```bash
cd dev
npm install
npm run build
```

See `dev/README.md` for details. The utility safelist in `dev/admin.src.css`
is part of the plugin-developer contract documented in `docs/EXTENSIONS.md` —
keep both in sync.
