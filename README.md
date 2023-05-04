It is a simple todo application which is made by rest API in laravel v8.

These are the instructions to run this project:-

Firstly pull the project-
git pull https://github.com/ashik035/laravel_api.git

Then run these commands
composer update
npm i && npm run dev

Now rename the .env.exapmle to .env

Then run this command-
php artisan serve

update database name at env file and run-
php artisan migrate

Add these four variable to your env file
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=
PASSPORT_PASSWORD_CLIENT_ID=2
PASSPORT_PASSWORD_CLIENT_SECRET=

Now Run:
php artisan passport:install

Then update PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET and PASSPORT_PASSWORD_CLIENT_SECRET at the env file. 
php artisan optimize:clear

Now import the file called 'postman' from the root directory of the project to your postman and enjoy the project.


N.B: After running "php artisan migrate", everytime you need to run "php artisan passport:install" and update PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET and PASSPORT_PASSWORD_CLIENT_SECRET to your env
