# ShortLink URL Shortening Service Setup Guide

---

#### Prerequisites

Before you begin, ensure you have the following prerequisites installed on your system:
PHP (Laravel requires PHP)
Composer (PHP package manager)
Laravel (Install it via Composer: composer global require laravel/installer)

---

**Step 1:** Clone the Repository
Clone the ShortLink URL Shortening Service repository to your local machine using Git.
`git clone https://github.com/abrahamemmanuel/url-shortener.git`

**Step 2** Change into the project directory
`cd project-directory`

**Step 3** Install Dependencies
`composer install`

**Step 4** Configure the Environment. Create a copy of the .env.example file and name it .env.
`cp .env.example .env`

**Step 5** Generate an application key for Laravel.
`php artisan key:generate`

**Step 6** Run the Application. Start the Laravel development server.
`php artisan serve`
The application will be available at http://127.0.0.1:8000. You can access it in your web browser.

**Step 7** Use the ShortLink Service
You can now use the ShortLink service by accessing the following endpoints:

`/encode: Encode a URL to a shortened URL.`
`/decode: Decode a shortened URL to its original URL.`
`/statistic/{url_path}: Get basic statistics for a short URL path.`

You can use tools like Postman or cURL to send requests to these endpoints and test the functionality.
To run the tests, use the following command:
`php artisan test`

To run this API on POSTMAN click on the below link

[![Run in Postman](https://run.pstmn.io/button.svg)](https://documenter.getpostman.com/view/5744463/2s9YXcdQMd)

_Happy Testing!_
