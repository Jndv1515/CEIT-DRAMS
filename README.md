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
│   │   ├── documents/index.php # /documents
│   │   ├── errors/404.php
│   │   ├── home/index.php      # /dashboard content
│   │   ├── layouts/            # main.php, auth.php, dashboard.php, header.php, footer.php
│   │   ├── requests/create.php # /request form
│   │   └── requests/index.php  # /requests
│   └── helpers.php             # url(), e(), flash + request session helpers
├── assets/
│   ├── css/style.css           # Login + dashboard styles (dark by default)
│   ├── js/app.js               # Reserved for site-wide scripts
│   └── images/cvsu_logo.png
├── storage/logs/               # Runtime files (logs, uploads later)
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
| `/request`   | GET: request form; POST: submit a request     |
| `/requests`  | Submitted and sample requests                 |
| `/documents` | Available document types                      |
| `/logout`    | Redirects back to `/login`                    |

## Notes

- The login layout uses `app/views/layouts/auth.php` (no site header/footer), matching the reference.
- Colors match the reference's dark theme: dark zinc gradient, light text, white "Log in" button with dark text. Dark is the default regardless of the OS setting.
- Google sign-in is intentionally omitted; the email/password form is shown directly.
- Login and registration screens share `layouts/auth.php` and the same dark theme, with links to each other.
- Both auth screens use a two-column layout adapted from the target mockup: form on the left, branding and tagline on the right, stacking on narrow screens.
- The dashboard is centralized for student and organization requests: a Personal / Organization context switcher, request-type filters, and requester badges share one queue. It keeps the responsive sidebar, clickable stat cards, and labeled mobile rows in the dark/orange theme.
- Dashboard content comes from `app/data/mock_dashboard.php`; swap it for database queries when the backend is ready. Sample scenarios cover capstone/thesis and student organization document requests.
- The Document Forward Request module is frontend-only: `/request` validates and stores submissions in `$_SESSION`, redirects with a flash message, and the new Pending request appears at the top of Recent Requests and My Requests. Document types come from `app/data/documents.php`; the requester type is derived from the selected document, not from the dashboard context.
- `login.php` is kept only so existing links keep working; it redirects to `/login`.
- `index.css` is a stub; the real stylesheet is `assets/css/style.css`.
- Authentication and the database layer are intentionally left unimplemented.
