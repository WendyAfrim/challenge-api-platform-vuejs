php-bash:
	docker compose exec -it php /bin/sh

node-bash:
	docker compose exec -it node /bin/sh

docker-up:
	docker compose up -d

docker-stop:
	docker compose down