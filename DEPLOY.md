# Deploying the Pasora site to Hostinger via GitHub

The site is a **static** HTML/CSS/JS page. The `admin/` folder adds a small,
password-protected PHP panel so you can **publish the latest code from a
backend "Deploy now" button** — no computer or FTP client needed.

```
GitHub (source of truth)  ──►  Hostinger public_html  ──►  www.pasorasolution.com
          ▲                               ▲
          │ git push (you / your team)    │ pull is triggered by:
          │                               │   • Hostinger auto-deploy (on push), and/or
          │                               │   • the /admin "Deploy now" button
```

Hostinger web hosting runs **LiteSpeed**, which honours `.htaccess`, so the
security headers already in `.htaccess` apply as-is. 

---

## 1. Put the code on GitHub

From this folder (`E:\pasora-website`), the repo is already initialised. Create
an **empty** repo on github.com (no README), then:

```bash
git add .
git commit -m "Pasora website + deploy admin"
git remote add origin https://github.com/<your-user>/<your-repo>.git
git push -u origin main
```

`admin/config.local.php`, `*.log` and `.DS_Store` are git-ignored, so no secrets
are ever pushed.

## 2. Connect the repo in Hostinger (hPanel → Git)

1. hPanel → **Websites → Manage → Advanced → GIT**.
2. **Create a new repository**:
   - Repository: `https://github.com/<your-user>/<your-repo>.git`
   - Branch: `main`
   - Directory: `public_html`  *(leave empty to deploy to the web root)*
3. Click **Create**. Hostinger does the first checkout into `public_html`.
   - If the repo is private, add Hostinger's SSH deploy key to GitHub first
     (hPanel shows the key; GitHub → repo → Settings → Deploy keys → Add).

### Auto-deploy on every push (recommended)
On the same Git page, open the repo you just added and copy the
**auto-deployment webhook URL**. In GitHub → repo → **Settings → Webhooks →
Add webhook**, paste it as the Payload URL, content type
`application/json`, event **push**. Now every `git push` auto-publishes.

> Keep that webhook URL — the admin "Deploy now" button uses the **same URL**
> to let you publish manually from the browser.

## 3. Set up the admin panel

1. Visit `https://your-domain/admin/`.
2. First run shows **Create your admin password** — set one (min 10 chars).
   It is stored only as a one-way hash in `admin/config.local.php`.
3. Tell the panel how to deploy. In `/admin/`, click **Settings** (top-right):
   - **Deploy method**: `webhook` (default, no SSH needed).
   - **Deploy webhook URL**: paste the auto-deployment URL from hPanel → Git.
   - Save. The **Deploy now** button is enabled once the URL is set.

   (No File Manager needed — the Settings page writes `config.local.php` for
   you. You can still edit that file by hand if you prefer.)

That's it: push to GitHub whenever you like, and either let auto-deploy run or
click **Deploy now** in `/admin/` to publish on demand.

---

## Deploy modes

| Mode        | What "Deploy now" does            | Needs                                   |
|-------------|-----------------------------------|-----------------------------------------|
| `webhook`   | POSTs to your deploy URL          | The hPanel auto-deploy webhook URL. Works on any plan. **Recommended.** |
| `git`       | Runs `git pull` on the server     | SSH + git enabled, `shell_exec` allowed, a `.git` folder in `public_html` (Business/Cloud plans). |

To use `git` mode, set `'deploy_mode' => 'git'` and `'git_path'` to your
`public_html`. The optional `admin/webhook.php` can also auto-pull on push in
this mode (set `github_webhook_secret` and point a GitHub webhook at it) — skip
it if you're using Hostinger's own auto-deploy.

## Security notes

- `admin/config.local.php` and `*.log` are blocked from the web by
  `admin/.htaccess` and are git-ignored.
- Login uses a hashed password, a CSRF token on the deploy action, and a
  5-attempt lockout. Set the password immediately after the first deploy.
- Serve the site over HTTPS (free SSL in hPanel → SSL). Then uncomment the
  **HSTS** and **HTTP→HTTPS redirect** blocks at the bottom of `.htaccess`.

## Before go-live (content, not deployment)

- **Real phone number** — `+233 00 000 0000` is a placeholder in
  `index.html` (contact block, footer, and the `telephone` in the JSON-LD).
- **Domain** — canonical/OpenGraph/schema URLs and `sitemap.xml`/`robots.txt`
  use `https://pasorasolutionltd.com/` (the live domain). Update these if the
  domain ever changes.
- **Contact email** — `index.html` still shows `info@pasorasolution.com`;
  confirm the real mailbox (e.g. `info@pasorasolutionltd.com`).
