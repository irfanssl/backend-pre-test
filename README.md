# Requirement 
DB connection : sqlite (ini karena untuk mempermudah proses debugging dan unit testing)




# Installation
run :
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed



# Runnning
run : 
php artisan serve




# Testing
php artisan test
NB : masih ada 2 test case yg gagal (ketika update task), ini saya pusing bgt sumpah. maaf ya



# Documentation
sudah tersedia sesuai L5-swagger
di halaman : /api/documentation

jika ingin generate documentation :
php artisan l5-swagger:generate


