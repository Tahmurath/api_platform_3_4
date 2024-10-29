git clone https://github.com/Tahmurath/api_platform_3_4.git

docker compose build --no-cache

docker compose up --wait

docker compose exec php bin/console doctrine:migrations:diff
docker compose exec php bin/console doctrine:migrations:migrate
docker compose exec pwa pnpm create @api-platform/client

set -e
apt-get install openssl
php bin/console lexik:jwt:generate-keypair
setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt

curl -k -X 'POST' 'https://localhost/companies' -H 'accept: application/ld+json' -H 'Content-Type: application/ld+json' -d '{
"name": "Company"
}'

curl -k -X 'POST' 'https://localhost/users'  -H 'accept: application/ld+json' -H 'Content-Type: application/ld+json' -d '{
"email":"123457@123456.com","password":"123456@123456.com","plainPassword":"123456@123456.com","role":"ROLE_USER","name":"Dfdsdfsdf","company":"/companies/1"
}'

curl -k -X 'POST' 'https://localhost/auth' -H 'accept: application/json' -H 'Content-Type: application/json' -d '{
"email": "eeeee@sdfsdf.com",
"password": "eeeee@sdfsdf.com"
}'

curl -k -X 'GET' 'https://localhost/users?page=1' -H 'accept: application/ld+json' -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzAyMDY5NDUsImV4cCI6MTczMDIxMDU0NSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiZWVlZWVAc2Rmc2RmLmNvbSJ9.z6_hNoS8l-vEFzpxnt6WlTgnWGsA9vBBrqdvdxtm3fDiteZz7xcRzujnDU-8jgCLz9Dz1o3ox76v6-79mwhDCN-UNiwW8VlDWIYUleSrrX4OJfbhJiVCCEzW7dUDyKde5nPbj2cZJqBFNr9fy5qFotiQykN5y6V9XRCVlrArTIbqKt7HzQaIE3Jh1oyvUXiJ4BPFEE70RQQ3O7JdYYnRq3jgZd_NVXzCMHsGFqQfCHYbh7Y3EEONSyyL5_T6POXmgkhSPByb_0fbLux-Y5K_-xgOlFbYUunkbKDlhEzJ0L0FsT-gQ0xtYEnhZmqI30xxt1c49syD4L9tIbyAmUONPw'

