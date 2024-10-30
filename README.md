git clone https://github.com/Tahmurath/api_platform_3_4.git


docker compose build --no-cache


docker compose up --wait



docker compose exec php bin/console doctrine:migrations:diff
docker compose exec php bin/console doctrine:migrations:migrate


set -e
apt-get install openssl
php bin/console lexik:jwt:generate-keypair


set -e
apt-get install openssl
php bin/console lexik:jwt:generate-keypair



sudo docker compose exec php bin/console doctrine:database:create --env=test

sudo docker compose exec php bin/console doctrine:migrations:migrate --env=test
sudo docker compose exec php bin/phpunit
