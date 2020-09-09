export PROJECT_IP = 127.0.0.1

DOCKER = docker
DOCKER_COMPOSE = docker-compose
PHP = docker exec backend_php_1
PHP_CLI = docker exec -it backend_php_1 /bin/bash
DOCKER_IMAGES_LIST := $(docker images -qa -f dangling=true)


start: ##@development bring up dev environment
	$(DOCKER_COMPOSE) up
.PHONY: start

stop: ##@development stop servers
	$(DOCKER_COMPOSE) stop -t 1
.PHONY: stop

clean: stop ##@setup stop and remove containers
	$(DOCKER_COMPOSE) down --remove-orphans
	@if [ -n "$(DOCKER_IMAGES_LIST)" ]; then \
        $(DOCKER) rmi "$(DOCKER_IMAGES_LIST)"; \
    fi
	#$(MAKE) clean-vendor
.PHONY: clean

build: ##@setup build docker images
	$(DOCKER_COMPOSE) build
.PHONY: build

setup: ##@setup Create dev enviroment
	@if [ "$(OS)" != "Windows_NT" ]; then grep -q "^${PROJECT_IP} db$$" /etc/hosts || sudo sh -c "echo '${PROJECT_IP} db' >> /etc/hosts"; fi
	@if [ "$(OS)" != "Windows_NT" ]; then grep -q "^${PROJECT_IP} backend$$" /etc/hosts || sudo sh -c "echo '${PROJECT_IP} backend' >> /etc/hosts"; fi
	@if [ "$(OS)" != "Windows_NT" ]; then grep -q "^${PROJECT_IP} php$$" /etc/hosts || sudo sh -c "echo '${PROJECT_IP} php' >> /etc/hosts"; fi
	@if [ "$(OS)" != "Windows_NT" ]; then grep -q "^${PROJECT_IP} localunixsocket\\.local$$" /etc/hosts || sudo sh -c "echo '${PROJECT_IP} localunixsocket.local' >> /etc/hosts"; fi
	$(MAKE) composer-install
	$(MAKE) migrate
	$(PHP) sh -c "cd /app && npm install"
	$(PHP) sh -c "cd /app && npm run dev"
.PHONY: setup

clean-vendor: ##@development remove vendor
	@if [ -d "vendor" ]; then rm -rf vendor; fi
.PHONY: clean-vendor

composer-install: ##setup install composer packages
	$(PHP) sh -c "cd /app && composer install"
.PHONY: composer-install

php-cli: ##@development php container cli
	$(DOCKER) exec -it backend_php_1 /bin/bash
.PHONY: php-cli

migrate: ##@development execute phinx migrations
	$(PHP) sh -c "cd /app && php artisan migrate:fresh --seed"
.PHONY: migrate

npm-install: ##setup install composer packages
	$(PHP) sh -c "cd /app && npm install"
	$(PHP) sh -c "cd /app && npm run dev"
.PHONY: npm-install
