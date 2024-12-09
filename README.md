Getting Started

Follow these steps to get the project up and running on your local machine.
1. Clone the Repository

First, clone the repository to your local machine:

git clone https://github.com/your-username/your-project.git

2. Install PHP Dependencies

Navigate to the project directory and install the PHP dependencies using Composer:

cd your-project
composer install

3. Set Up Your Environment

If you haven't already, create an .env file in the project root directory by copying the example file:

cp .env.example .env

Then, generate the application key:

php artisan key:generate

Make sure to configure your .env file with the correct database and other settings for your environment.
4. Install JavaScript Dependencies

In the project directory, install the JavaScript dependencies using npm:

npm install

5. Run the Development Server

To start the application, first start the Laravel server using php artisan serve:

php artisan serve

This will start the PHP server at http://localhost:8000.

Next, in a separate terminal window, run the npm development server:

npm run dev

This will compile your assets (CSS, JavaScript) and start a local development server at http://localhost:3000 (by default).
6. Access the Application

Once both servers are running, you can access the application in your browser at http://localhost:8000 or http://localhost:3000.
Running Tests

If you have tests set up for your project, you can run them using:

php artisan test
