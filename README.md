User Name: admin@admin.com
pass: password

Installation

1. config .env
2.composer update
3.composer require laravel/passport
4.Run the migrations
5.run php artisan db:seed   //for defaul admin user
6. run php artisan passport:install     //generate key
7.run npm update and npm run dev   //when ecnounter dev tool source mapping issue

npm update and npm run dev

--------------------------------------------------------------------------------------------------------
Expense Manager
-A simple Laraveland  Vue application that tracks expenses.

Feautures:
1. User Management

        -User Role persmission
        
2. Expense Management

        -Keeps track of user's expenses
        
3. Dashboard

        -Display Chart of expense per Category
