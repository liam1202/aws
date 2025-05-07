Peer-to-Peer Payment App - Documentation
Overview
•	App for users to register, log in, send/request money, view history, add funds, and manage requests.
•	Uses HTML for UI, PHP for backend logic, MySQL for storage, and JS for interactivity on the HTML files.

Main Files and Their Purposes
Frontend (HTML + JS)
•	index.html – Login form for users.
•	register.html – User registration form.
•	history.html – Shows user’s balance, transaction history, and main navigation.
•	transaction.html – Form to send/request money.
•	pending.html – Displays pending requests to accept/decline.
•	account.html – Add funds to your account (shows card number for logged in user).
•	search.html – Search for users by username/email.
•	style.css – Styles for all pages.
•	loadRequests.js – Loads pending requests (AJAX) to pending.html.
Backend (PHP)
•	db.php – Database connection details, included in all backend scripts.
•	create_user.php – Handles registration, checks for duplicates, creates user and account (with random card number).
•	login.php – Authenticates user, starts session.
•	logout.php – Logs out and destroys session.
•	transactions.php – Handles sending/requesting money and updates database.
•	history.php – Returns user balance and transaction history.
•	load_requests.php – Shows logged-in user’s pending payment requests.
•	handle_requests.php – Handles accepting/declining requests.
•	add_to_account.php – Adds money (with 2-day delay logic).
•	session_check.php – Checks if session is still active (AJAX endpoint).

Database Structure
•	Users
o	UserID (PK)
o	Username (unique)
o	Email (unique)
o	PasswordHash
o	CreatedAt
•	Accounts
o	AccountID (PK)
o	UserID (FK)
o	Balance (Decimal)
o	CardNumber (16-digit, unique, random)
o	CreatedAt
o	UpdatedAt
•	Transactions
o	TransactionID (PK)
o	SenderID (FK)
o	ReceiverID (FK)
o	Amount (Decimal)
o	TransactionDate
•	Requests
o	RequestID (PK)
o	RequestorID (FK)
o	RequesteeID (FK)
o	Amount (Decimal)
o	Status (PENDING/ACCEPTED/DECLINED)
o	RequestDate

How To Use
•	Register:
o	Go to index.html
o	Select the register button
o	Fill username, email, password, and starting balance
o	A random card number is generated to represent a bank card
•	Log In:
o	Use index.html
o	Enter username and password
o	On success, sent to history.html
•	View History:
o	history.html shows your username, current balance, transaction history
o	Navigation buttons to search users, add funds, or view pending requests
	The button to direct to pending requests only appears if the logged in user actually has requests pending
•	Send or Request Money:
o	Use transaction.html
o	Enter recipient user ID (autofilled from search or request)
o	Enter amount, choose to send/request
o	Success redirects to history.html
•	Search Users:
o	Use search.html
o	Search by username or email
o	If user found, redirected to transaction.html with user ID filled
•	Manage Requests:
o	Go to pending.html
o	See pending requests for you
o	Accept: Goes to transaction form (autofills details)
	If funds insufficient, status remains pending, alert shown
o	Decline: Removes request
•	Add Money:
o	Go to account.html
o	Enter amount (shows logged in user’s card number)
o	Money added after 2-day delay
•	Log Out:
o	Use "Back to Login" button (calls logout.php)
o	Session destroyed
o	Pressing back reloads and checks session; if not logged in, alert and redirect back to index

Security/Validation Notes
•	Duplicate username/email checks on registration.
•	Passwords are hashed.
•	Prepared statements for SQL in php scripts.
•	Session required for all user actions.
•	If user not logged in, all backend scripts redirect to login.
•	"Back" button after logout reloads and checks session status.
•	User cannot access others' data by changing user IDs.


How to Install/Run
•	Import SQL schema to your MySQL server.
•	Edit db.php for your DB credentials.
•	Upload all files to your PHP web server. (EC2)
•	Open register.html in your browser to create the first user.
•	App is now ready to use.

Troubleshooting
•	Duplicate registration: Usernames/emails must be unique.
•	Session issues: Clear browser cookies or restart server.
•	Page not updating after logout: Make sure session checks are in place on all pages.
•	Database errors: Ensure all tables/columns exist as shown above.
