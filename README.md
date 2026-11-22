# CEIT — Document Request and Approval Management System (DRAMS)

## Run locally

1. Start **Apache** in XAMPP.
2. Open <http://localhost/ceit-drams/>.
3. Make sure Apache's `mod_rewrite` is enabled (it is by default in XAMPP) so routes
   such as `/login` work.

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
│   │   ├── mock_dashboard.php  # Sample dashboard/request records
│   │   └── documents.php       # Available document types
│   ├── models/                 # Model classes go here (future database layer)
│   ├── views/
│   │   ├── auth/login.php      # CvSU-style email/password login UI
│   │   ├── auth/register.php   # Matching registration UI
│   │   ├── admin/index.php     # /admin staff queue
│   │   ├── components/         # Shared fragments (e.g. decision-actions.php)
│   │   ├── errors/404.php
│   │   ├── home/index.php      # /dashboard content
│   │   ├── home/profile.php    # /profile account details
│   │   ├── layouts/            # main.php, auth.php, dashboard.php, admin.php, header.php, footer.php
│   │   │   └── partials/       # Shared theme head/toggle/icon fragments
│   │   ├── requests/create.php # /request form
│   │   ├── requests/index.php  # /requests tabs: requests + document catalog cards
│   │   └── requests/show.php   # /request-details
│   └── helpers.php             # url(), e(), flash + request session helpers
├── assets/
│   ├── css/style.css           # Login + dashboard styles (dark by default, light via tokens)
│   ├── js/app.js               # Frontend file preview for request uploads
│   ├── js/admin.js             # Staff decision confirmation dialog
│   ├── js/documents.js         # Document catalog preview dialog
│   ├── js/theme.js             # Light/dark toggle persistence
│   └── images/cvsu_logo.png
├── storage/
│   ├── logs/                   # Runtime logs
│   └── uploads/                # Request attachments (not web-accessible)
├── .htaccess                   # Rewrite rules + access restrictions
├── index.php                   # Front controller — all routes enter here
├── login.php                   # Legacy redirect to /login
└── fiveserver.config.js        # Five Server PHP path
```

## Adding a page

1. Add a method to a controller in `app/controllers/`, e.g. `RequestsController::index()`.
2. Register the route in `index.php`:
   ```php
   $router->get('/requests', [RequestsController::class, 'index']);
   ```
3. Create `app/views/requests/index.php` and call
   `$this->view('requests/index', ['title' => 'Requests'])` from the controller.

## Routes

| Route        | Displays                                      |
| ------------ | --------------------------------------------- |
| `/`          | Login screen (default display)                |
| `/login`     | Same login screen                             |
| `/register`  | Registration screen (same style)              |
| `/dashboard` | Centralized request dashboard                 |
| `/profile`   | Account details and request activity          |
| `/admin`     | Admin/staff queue with status filters         |
| `/admin/action` | POST: approve, reject, forward, or reset   |
| `/request`   | GET: request form; POST: submit a request     |
| `/requests`  | Tabs: My Requests + Available Documents       |
| `/request-details` | Details for one submitted request      |
| `/documents` | Document catalog: cards, filters, preview      |
| `/attachment`| Streams a session request's uploaded file; `?inline=1` renders it in the preview dialog |
| `/logout`    | Redirects back to `/login`                    |

## Notes

- The login layout uses `app/views/layouts/auth.php` (no site header/footer), matching the reference.
- Colors match the reference's dark theme: dark zinc gradient, light text, white "Log in" button with dark text. Dark is the default regardless of the OS setting.
- A light/dark toggle sits in the sidebar utility area above Logout on dashboard/admin pages, and as a compact top-right icon button on auth pages. Its text and icon describe the destination ("Switch to light mode" with a sun in dark mode; "Switch to dark mode" with a moon in light mode), so the label always matches what a click does. The choice is stored in `localStorage` and applied before the stylesheet loads to avoid a flash. Themes are token-only overrides under `[data-theme="light"]`, so components need no theme-specific code.
- Google sign-in is intentionally omitted; the email/password form is shown directly.
- Login and registration screens share `layouts/auth.php` and the same dark theme, with links to each other.
- Both auth screens use a two-column layout adapted from the target mockup: form on the left, branding and tagline on the right, stacking on narrow screens.
- The dashboard is centralized for student and organization requests: a Personal / Organization context switcher, request-type filters, and requester badges share one queue. It keeps the responsive sidebar, clickable stat cards, and labeled mobile rows in the dark/orange theme.
- Dashboard content comes from `app/data/mock_dashboard.php`; swap it for database queries when the backend is ready. Sample scenarios cover capstone/thesis and student organization document requests.
- The Document Forward Request module is frontend-only: `/request` validates and stores submissions in `$_SESSION`, redirects with a flash message, and the new Pending request appears at the top of Recent Requests and My Requests. Document types come from `app/data/documents.php`; the requester type is derived from the selected document, not from the dashboard context.
- Tracking and the document catalog are consolidated under `/requests` with My Requests / Available Documents tabs; `/documents` opens the catalog directly. My Requests stays a table with View (request details) and Download (attachment) actions, plus an empty state when there are no requests.
- The catalog is a searchable set of document cards with requester-type and availability filters and name/category sorting. Availability (Available to download, Request pending, Request again, Request required) is derived from the current session's request statuses; each card shows format, size, and eligibility. Cards link to Request, Track/View request, or Download/Preview depending on state, and a "Showing X of Y documents" count plus an empty state cover filtered results.
- Previewable attachments (PDF, PNG, JPG, JPEG) open in a keyboard-accessible dialog driven by `assets/js/documents.js`: focus moves to the close button on open and returns to the Preview button on close. `/attachment?request=<id>&inline=1` serves the file inline for the dialog, while the plain `/attachment?request=<id>` link still downloads it.
- The request form includes a frontend-only attachment preview using the browser File API: PDF, PNG, JPG, and JPEG render inline before submission; DOC/DOCX show a fallback message.
- A frontend-only admin/staff dashboard lives at `/admin`. It shares the same request queue, adds Pending / Approved / Forwarded / Rejected filters, and lets staff Approve, Forward, Reject, or Reset requests. Status changes are stored in `$_SESSION['request_statuses']` and are reflected on the requester dashboard, My Requests, and request details. Since authentication is still mocked, `/admin` is a demo entry point rather than a permission-gated area.
- Optional supporting files (PDF, DOC, DOCX, PNG, JPG, JPEG; max 5 MB) are moved to `storage/uploads/` and served through `/attachment?request=<id>`. The uploads directory is blocked from direct web access; swap this for database-backed storage when the backend is ready.
- `/profile` shows the demo user's personal information and request activity from `app/data/mock_dashboard.php` and the session queue. The sidebar Profile link now opens this page; editing is deferred until authentication and the database layer are implemented.
- `login.php` is kept only so existing links keep working; it redirects to `/login`.
- `index.css` is a stub; the real stylesheet is `assets/css/style.css`.
- Authentication and the database layer are intentionally left unimplemented.
