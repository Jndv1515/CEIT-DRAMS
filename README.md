# CEIT DRAMS: Document Request and Approval Management System

A PHP application for requesting, tracking, and approving CEIT documents. Students can submit document requests with attachments; admin/staff can review, approve, forward, or reject them.

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.x)
- Git

## Setup

### 1. Clone the repository

```bash
git clone https://github.com/Jndv1515/CEIT-DRAMS C:/xampp/htdocs/DRAMS/ceit-drams
```

### 2. Create the database

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Open **phpMyAdmin** at <http://localhost/phpmyadmin>.
3. Click **Import** in the top navigation.
4. Click **Choose File** and select `setup.sql` from the project folder.
5. Click **Go**. This creates the `ceit_drams` database and both tables (`users` and `document_requests`) automatically.

### 3. Configure your environment

Open the `.env` file in the project root and update the values to match your local XAMPP setup:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=ceit_drams
DB_USER=root
DB_PASS=
```

The default values work for a standard XAMPP installation (no password on `root`). If you set a MySQL root password, enter it in `DB_PASS`.

### 4. Enable Apache mod_rewrite

1. Open `C:/xampp/apache/conf/httpd.conf` in a text editor.
2. Find the line `#LoadModule rewrite_module modules/mod_rewrite.so` and remove the `#`.
3. Find the `<Directory "C:/xampp/htdocs">` block and change `AllowOverride None` to `AllowOverride All`.
4. Restart Apache in the XAMPP Control Panel.

### 5. Open the app

Go to <http://localhost/DRAMS/ceit-drams/> in your browser. You should see the login screen.

### 6. Create your first account

Click **Register**, fill in your details, and choose your role. After registering, log in with your email and password.

---

## Structure

```
ceit-drams/
├── app/                        # Application code (blocked from direct web access)
│   ├── config/
│   │   └── config.php          # Constants, session start, autoloader
│   ├── controllers/
│   │   ├── AdminController.php    # Staff queue + status actions
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   └── RequestController.php  # Request form, submission, and listings
│   ├── core/
│   │   ├── Controller.php      # Base controller: view() + redirect()
│   │   ├── Router.php          # GET/POST route table and dispatch
│   │   └── View.php            # View rendering with layout
│   ├── data/
│   │   ├── mock_dashboard.php  # Sample dashboard user, stats, and requests
│   │   └── documents.php       # Available document types
│   ├── models/                 # App\Models\ autoloads from here
│   ├── views/
│   │   ├── auth/login.php      # CvSU-branded email/password login UI
│   │   ├── auth/register.php   # Registration UI
│   │   ├── admin/index.php     # /admin staff queue
│   │   ├── components/         # Shared fragments (e.g. decision-actions.php)
│   │   ├── errors/404.php
│   │   ├── home/index.php      # /dashboard content
│   │   ├── home/profile.php    # /profile account details
│   │   ├── layouts/            # main.php, auth.php, dashboard.php, admin.php, header.php, footer.php
│   │   │   └── partials/       # theme-head.php, theme-icon.php, theme-sidebar.php
│   │   ├── requests/create.php # /request form
│   │   ├── requests/index.php  # /requests tabs: requests + document catalog cards
│   │   └── requests/show.php   # /request-details
│   └── helpers.php             # url(), e(), set_flash(), take_flash(), request helpers
├── assets/
│   ├── css/style.css           # Theme tokens and component styles
│   ├── js/app.js               # Tabs keyboard support and request upload preview
│   ├── js/admin.js             # Staff decision confirmation dialog
│   ├── js/documents.js         # Document catalog preview dialog
│   ├── js/theme.js             # Light/dark toggle persistence
│   └── images/cvsu_logo.png
├── storage/
│   ├── logs/                   # Directory reserved for runtime logs
│   └── uploads/                # Request attachments (blocked from direct web access)
├── .htaccess                   # Rewrite rules + access restrictions
├── index.php                   # Front controller; all routes enter here
├── login.php                   # 301 redirect to /login
└── fiveserver.config.js        # Five Server PHP path
```

## Adding a page

1. Add a method to a controller in `app/controllers/`. For example, `/requests` is handled by `RequestController::index()` in `app/controllers/RequestController.php`.
2. Register the route in `index.php`, with the matching `use App\Controllers\RequestController;` import:
   ```php
   $router->get('/requests', [RequestController::class, 'index']);
   ```
3. Create the view under `app/views/` and pass its path to `view()`. Pass the layout explicitly to use the dashboard sidebar:
   ```php
   $this->view('requests/index', ['title' => 'Requests'], 'layouts/dashboard');
   ```
   `view()` uses `layouts/main` when no layout argument is given.

## Routes

| Route              | Displays                                                                              |
| ------------------ | ------------------------------------------------------------------------------------- |
| `/`                | Login screen (default display)                                                        |
| `/login`           | Login screen; POST redirects to `/dashboard`                                          |
| `/register`        | Registration screen; POST redirects to `/login`                                       |
| `/dashboard`       | Request dashboard                                                                     |
| `/profile`         | Account details and request activity                                                  |
| `/admin`           | Staff queue with status filters                                                       |
| `/admin/action`    | POST: approve, forward, reject, or reset a request                                    |
| `/request`         | GET: request form; POST: submit a request                                             |
| `/requests`        | Tabs: My Requests + Available Documents                                               |
| `/request-details` | Details for one request; expects `?id=<id>`                                           |
| `/documents`       | Document catalog: cards, filters, preview                                             |
| `/attachment`      | Streams `?request=<id>`; `&inline=1` serves the file inline instead of downloading it |
| `/logout`          | Redirects to `/login`                                                                 |

## How it works

- `/` and `/login` render `auth/login.php`; `/register` renders `auth/register.php`. Both use `layouts/auth.php`, which omits the site header and footer. The views use email/password fields and link to each other. There is no Google sign-in option.
- The auth grid is one column below 48rem and splits into form on the left, branding on the right at 48rem and wider.
- `/dashboard` reads the demo user, stats, and sample requests from `app/data/mock_dashboard.php`, then prepends requests submitted in the session. It has a Personal / CEIT Student Council context switcher, Student / Organization filters, requester badges, and stat cards that link to `/requests`. Both contexts share one Recent Requests queue.
- `/request` checks that the document exists in `app/data/documents.php` and that the purpose is non-empty. The requester type comes from the selected document, not from the dashboard context. Submissions get an ID such as `DR-20261122-0001`, are stored in `$_SESSION['document_requests']`, and appear at the top of Recent Requests and My Requests.
- `/requests` has My Requests and Available Documents tabs; `/documents` opens the catalog tab directly. My Requests is a table with View and Download actions and an empty state. The catalog searches names, categories, descriptions, and eligibility; filters by requester type and availability; and sorts by name or category. Availability labels (Available to download, Request pending, Request again, Request required) come from session request statuses. Filtered results show a "Showing X of Y documents" count and an empty state.
- `/admin` uses the same queue with `layouts/admin`. Status filters are All, Pending, Processing, Approved, Rejected, and Forwarded, plus search and sort. Pending and processing requests offer Approve, Forward, and Reject; other statuses offer Reset. The dialog in `assets/js/admin.js` requires a rejection reason of at least 10 characters, and the controller rejects an empty reason. Status overrides go to `$_SESSION['request_statuses']` and appear on the dashboard, in My Requests, and on the details page.
- `/profile` shows the demo user's details from `app/data/mock_dashboard.php` and request activity from the session queue. It is read-only.

## Attachments and previews

- `/request` accepts an optional PDF, DOC, DOCX, PNG, JPG, or JPEG up to 5 MB. The controller checks the extension and MIME type, stores the file in `storage/uploads/` under a generated name, and serves it through `/attachment?request=<id>`. Without `&inline=1`, the response uses `Content-Disposition: attachment`.
- `assets/js/app.js` previews a selected PDF, PNG, JPG, or JPEG in the form with `URL.createObjectURL`; DOC and DOCX show a fallback message. Server-side checks still run on submit.
- Catalog previews use `assets/js/documents.js` for PDF, PNG, JPG, and JPEG attachments. The dialog moves focus to its close button on open and back to the Preview button on close. Browsers without native `<dialog>.showModal()` open the attachment URL directly.
- `storage/uploads/.htaccess` denies direct requests, and the root `.htaccess` blocks `storage/` as well.

## Theming

- `assets/css/style.css` defines tokens in `:root` (zinc surfaces, white accents with dark foreground, orange brand colors) and overrides them under `[data-theme="light"]`. Components use the tokens and need no theme-specific code.
- Every layout loads `partials/theme-head.php` before the stylesheet. It reads `localStorage['ceit-drams-theme']` and sets `data-theme` to `dark` only for that exact saved value; otherwise it sets `light`. The OS color scheme is not consulted. `assets/js/theme.js` writes the key when the toggle is used.
- Dashboard and admin layouts place `partials/theme-sidebar.php` above Logout. Its visible label and icon name the destination: "Light" with a sun in dark mode, "Dark" with a moon in light mode. The `aria-label` is "Switch to light mode" or "Switch to dark mode". Auth and `main` layouts use the fixed top-right `partials/theme-icon.php` button instead.

## Notes

- `login.php` at the root exists only to redirect old `/login.php` links to `/login` with a 301.
- `fiveserver.config.js` points Five Server at `C:\xampp\php\php.exe`. Edit that path if PHP is installed elsewhere.
