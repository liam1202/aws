# 💸 Peer-to-Peer Payment App

A simple web application that allows users to register, log in, send/request money, view transaction history, add funds, and manage payment requests.

## 🧭 Overview

- HTML for UI
- PHP for backend logic
- MySQL for database storage
- JavaScript for interactivity (AJAX, DOM manipulation)

---

## 📁 Main Files and Their Roles

### Frontend (HTML + JS)
| File             | Purpose |
|------------------|---------|
| `index.html`     | User login form |
| `register.html`  | User registration form |
| `history.html`   | Displays balance, transaction history, and navigation |
| `transaction.html` | Send/request money |
| `pending.html`   | View and manage pending requests |
| `account.html`   | Add funds to account, view card number |
| `search.html`    | Search for users by username or email |
| `style.css`      | Styling for all pages |
| `loadRequests.js`| Loads pending requests via AJAX for `pending.html` |

### Backend (PHP)
| File                  | Purpose |
|-----------------------|---------|
| `db.php`              | Database connection logic |
| `create_user.php`     | Handles user registration and account creation |
| `login.php`           | Authenticates users and starts session |
| `logout.php`          | Destroys session and logs out user |
| `transactions.php`    | Handles sending/requesting money |
| `history.php`         | Returns balance and transaction history |
| `load_requests.php`   | Loads pending requests for logged-in user |
| `handle_requests.php` | Accept/decline payment requests |
| `add_to_account.php`  | Adds funds to account with 2-day delay logic |
| `session_check.php`   | AJAX endpoint to verify session is active |

---

## 🗃️ Database Schema

### `Users`
- `UserID` (PK)
- `Username` (unique)
- `Email` (unique)
- `PasswordHash`
- `CreatedAt`

### `Accounts`
- `AccountID` (PK)
- `UserID` (FK)
- `Balance` (Decimal)
- `CardNumber` (16-digit, unique)
- `CreatedAt`
- `UpdatedAt`

### `Transactions`
- `TransactionID` (PK)
- `SenderID` (FK)
- `ReceiverID` (FK)
- `Amount` (Decimal)
- `TransactionDate`

### `Requests`
- `RequestID` (PK)
- `RequestorID` (FK)
- `RequesteeID` (FK)
- `Amount` (Decimal)
- `Status` (`PENDING`, `ACCEPTED`, `DECLINED`)
- `RequestDate`

---

## 🚀 How to Use the App

### 🔐 Register
- Go to `index.html`, click **Register**
- Fill out the form
- System auto-generates a unique card number

### 🔑 Log In
- Enter your credentials on `index.html`
- Redirected to `history.html` upon success

### 📜 View History
- `history.html` displays balance and transaction history
- Navigation available to search users, add funds, or view pending requests

### 💸 Send/Request Money
- Go to `transaction.html`
- Enter recipient ID, amount, and choose to send/request
- Redirects to `history.html` on success

### 🔍 Search Users
- Use `search.html` to find users by username/email
- Found users redirect to `transaction.html` with autofilled ID

### 🕒 Manage Requests
- Go to `pending.html`
- Accept: opens transaction form with autofilled data (must have sufficient funds)
- Decline: removes the request

### 💰 Add Funds
- Navigate to `account.html`
- Enter amount; funds added after a 2-day delay

### 🚪 Log Out
- Click **Back to Login** button
- Session is destroyed
- "Back" button checks session and redirects if not logged in

---

## 🔐 Security & Validation

- Unique checks on username/email during registration
- Passwords are hashed securely
- All SQL queries use prepared statements
- Sessions required for all actions
- All backend scripts verify active session
- User access restricted to their own data
- Session check enforced on page reload after logout

---

## 🛠️ Installation Guide

1. Import SQL schema into your MySQL server.
2. Update `db.php` with your MySQL credentials.
3. Upload all files to a PHP-compatible web server (e.g., AWS EC2).
4. I advise using PUTTY as an interface for EC2 as a way to upload/edit files to instance.
5. Open `register.html` in your browser to create the first user.
6. You're ready to go!

---

## 🧰 Troubleshooting

- **Duplicate registration**: Ensure username and email are unique.
- **Session not working**: Clear cookies or restart your PHP server.
- **Post-logout back button**: Ensure `session_check.php` is working correctly.
- **Database errors**: Confirm all tables match the structure provided above.
