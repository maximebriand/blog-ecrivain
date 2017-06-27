Blog Symfony (OPC)
=

1)   git clone https://github.com/maximebriand/blog-ecrivain.git
2)   cd blog-ecrivain
3)   composer install
4)   Edit app/config/parameters.yml and configure credentials to access a database.
5)   php bin/console doctrine:database:create
6)   php bin/console doctrine:schema:create
7)   php bin/console doctrine:fixtures:load
8)   php bin/console server:run
9)   Browse http://127.0.0.1:8000/
10)  If there is a 500 error please check https://symfony.com/doc/current/setup/file_permissions.html
11)  You can login as Jean Forteroche with password 1234