# Virtual pool microservices

## Endpoints

Generate Voucher code against each user.

POST http://localhost/voucher_code/generate
special_offer - int(ID for special offer)
expiry_date - date(mm/dd/yyyy)

Usage of voucher code.

POST http://localhost/voucher_code/use
email - email
voucher_code - 8 digit code generated for each user.

Get voucher code and discount against it for user.

GET http://localhost/user/voucherCode?email=schneider.gladyce@nicolas.com

## Database

Database/Migration - Database structure.

## Usage
1) Pull code in folder.
2) Create databases.
3) .env - Setup database connections.

4) Artisan command : php artisan migrate:refresh --seed
This command can be used on command prompt at root folder for creation of database tables and data initial data in it.

