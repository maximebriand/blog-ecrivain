Blog Symfony (OPC)
=

1)   git clone https://github.com/maximebriand/blog-ecrivain.git
2)   cd blog-ecrivain
3)   composer install
4)   Edit app/config/parameters.yml and configure credentials to access a database.
5)   php bin/console doctrine:database:create
6)   php bin/console doctrine:schema:create
7)   php bin/console doctrine:fixtures:load
8)   php bin/console assets:install --symlink
9)   php bin/console server:run
10)   Browse http://127.0.0.1:8000/
11) You can login as Jean Forteroche with password 1234