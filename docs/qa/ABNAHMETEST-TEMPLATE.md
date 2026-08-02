# Abnahmetest OnePagerCMS {VERSION} — Vorlage

> Kopie dieser Vorlage als `docs/qa/ABNAHMETEST-{VERSION}.md` anlegen und ausfüllen.
> Die Automatisierung liegt in `docs/qa/scripts/` und ist versionsunabhängig gebaut.

| | |
|---|---|
| **Release** | {VERSION} |
| **Getesteter Stand** | Commit/Tag: {COMMIT} |
| **Datum** | {DATUM} |
| **Tester** | {NAME} |
| **Testumgebung** | PHP {PHP-VERSION}, SQLite {SQLITE-VERSION}, {OS} |

## 1. Vorbereitung

1. **Release Notes lesen** (`docs/RELEASE-NOTES-{VERSION}.html`). Jede dort genannte
   Änderung bekommt mindestens einen Prüfpunkt in Abschnitt 5 (releasespezifisch).
   Bei neuen DB-Migrationen: Schema-Checks in `scripts/upgrade-env.sh` (U-Checks)
   nachziehen. Extension-Tabellen bleiben lazy und gehören **nie** in `update.php`
   (siehe `CLAUDE.md`).
2. **Upgrade-Basisversionen bestimmen**: `git tag` — mindestens die letzten drei
   getaggten Releases als Ausgangspunkt. Szenarien in `scripts/run-abnahmetest.sh`
   anpassen (Funktionen `scenario_s2..s4`, Tags/Namen/Ports).
3. **Marketplace-Bestand abfragen** (alle Items müssen auf Kompatibilität getestet
   werden, Themes mit `section-contact`-Override besonders):
   ```
   curl -s "https://marketplace.onepagercms.de/api/v1/items" | python3 -m json.tool
   ```
   Neue Items ⇒ eigene M-Checks bzw. manuelle Prüfpunkte ergänzen.
   **Abwärts-Check je Theme/Plugin:** das ZIP zusätzlich auf der *ältesten* CMS-
   Version installieren und rendern, die sein `requires_opcms` erlaubt — Templates
   dürfen neue Core-Funktionen (z. B. `opcms_esc()`, seit 1.2.1) nur mit
   `function_exists()`-/`class_exists()`-Guard nutzen, sonst 500er auf Altversionen
   (Lehre aus Lumen 1.0.1, siehe ABNAHMETEST-1.2.1.md).
4. **Voraussetzungen** auf dem Testrechner: `php` (mit pdo_sqlite), `sqlite3`,
   `curl`, `rsync`, `git`; Internetzugang für die Marketplace-Tests;
   SQLite ≥ 3.25 (RENAME COLUMN — wird von U0 geprüft).

## 2. Durchführung (automatisiert)

```bash
docs/qa/scripts/run-abnahmetest.sh            # kompletter Lauf S1–S5
docs/qa/scripts/run-abnahmetest.sh --skip-marketplace   # ohne Internet
```

Standard-Szenarien (bei jedem Release gleich, nur Tags rotieren):

| Szenario | Inhalt |
|---|---|
| S1 | Neuinstallation des Release-Kandidaten + Marketplace-/Theme-Tests (M-Checks) |
| S2–S4 | Upgrade von den letzten drei getaggten Versionen; S4 zusätzlich mit vorinstalliertem, aktivem Marketplace-Theme (Bestands-Theme muss Upgrade überleben) |
| S5 | Reine DB-Migration einer Ur-Installation (v1.0.0-Schema) über `update.php` |

Jedes Szenario endet mit dem Smoke-Test (`scripts/smoke-test.sh`), der die
Regressions-Basis abdeckt — diese Checks sind bei **jedem** Release Pflicht:

- **B**: Frontend, Datenerhalt-Marker, Login, Backend, Section anlegen/Text ändern,
  Additional Pages
- **S**: Session-Pflicht der Handler, CSRF-Abweisung, Fehlerseiten-Text, XSS-Stichprobe
- **C**: Kontaktformular inkl. Spamschutz (Honeypot, Time-Trap, Rate-Limit, Mail-Inhalt)
- **T/M**: aktives Theme nach Upgrade, Marketplace-Liste/Cache/Install/Aktivierung,
  Kontaktformular unter jedem Marketplace-Theme
- **U** (in `upgrade-env.sh`): update.php-Lauf, Lock-Datei, Schema, Datenerhalt,
  Zweitlauf-Sperre, Altdateien-Hinweis

Einzelne Bausteine lassen sich isoliert aufrufen (Debugging):

```bash
docs/qa/scripts/setup-env.sh <name> <port> [--tag vX.Y.Z] [--seed]
docs/qa/scripts/upgrade-env.sh <name>
docs/qa/scripts/smoke-test.sh <name> [--marker TEXT] [--marketplace] [--expect-theme SLUG]
```

**FAIL-Erkennung verifizieren** (einmal pro Release, damit der Test nicht
"immer grün" ist): einen Check absichtlich brechen, z. B.
`smoke-test.sh fresh --marker GIBT-ES-NICHT` ⇒ B2 muss FAIL melden.

## 3. Ergebnis (automatisiert)

Zusammenfassung des Laufs hier einfügen (`$QA_WORKDIR/meta/<name>/results.txt`):

| Szenario | Ergebnis | Bemerkung |
|---|---|---|
| S1 Neuinstallation | {PASS/FAIL} | |
| S2 Upgrade {TAG-3} | {PASS/FAIL} | |
| S3 Upgrade {TAG-2} | {PASS/FAIL} | |
| S4 Upgrade {TAG-1} + Theme | {PASS/FAIL} | |
| S5 Ur-Schema-Migration | {PASS/FAIL} | |

## 4. Manuelle Restprüfungen

Nach dem Lauf bleiben die Instanzen gestartet (URLs + Logins gibt das Skript aus).

| # | Prüfpunkt | Ergebnis | Bemerkung |
|---|---|---|---|
| M-1 | Visuelle Abnahme Admin-Backend (alle Seiten unter `core/`) im echten Browser | | |
| M-2 | Visuelle Abnahme Frontend mit jedem Marketplace-Theme (Desktop + mobil) | | |
| M-3 | Extension-Upload-Tab mit echtem ZIP (Plugin und Theme) | | |
| M-4 | Passwort-vergessen-Flow auf einer Instanz mit echtem Mailversand | | |
| M-5 | Login-Throttling: mehrere Fehlversuche ⇒ Sperre greift | | |
| M-6 | Bild-Uploads (Logo, Favicons, Section-Hintergrund) inkl. Ablehnung von Nicht-Bildern | | |
| M-7 | Releasespezifische Sichtprüfungen aus Abschnitt 5 | | |

## 5. Releasespezifische Prüfpunkte für {VERSION}

> Aus den Release Notes ableiten — jede Note ⇒ mindestens ein Punkt.

| # | Release-Note | Prüfpunkt | automatisiert? | Ergebnis |
|---|---|---|---|---|
| R-1 | {…} | {…} | {Check-ID / manuell} | |

## 6. Befunde

| # | Schwere | Befund | Konsequenz |
|---|---|---|---|
| F-1 | {Blocker/Major/Minor} | {…} | {…} |

## 7. Freigabe

Kriterium: alle automatisierten Checks PASS (oder Befund dokumentiert und als
nicht blockierend eingestuft), keine offenen Blocker aus Abschnitt 4–6.

- [ ] Freigabe erteilt am {DATUM} durch {NAME}
