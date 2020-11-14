# Installation

- Clone the repository.
- Open the terminal and run "cp .env.example .env" command. It will generate a .env file into the root directory
- Again run "php artisan key:generate" command.
- Now configure your database connection and add "STRIPE_SECRET=(stripe_secret) and STRIPE_KEY=(stripe_key)" in the .env file 
- Again run "php artisan serve" command in your terminal
- Now project is ready to browse on this "http://127.0.0.1:8000" link.  


# Issue/Task

- Registration 
    * Password not hashed
- Login
    * Form has @csrf missing 
    * Email field has no property of name
    * Password fields name properties value wrong
- Stripe payment gateway implemented
- Auto user inactivate process implemented
- Payments list view for each user Created
- Restrict wrong login attempt
    