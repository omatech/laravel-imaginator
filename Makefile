.PHONY: build install lint test validate audit check
IMAGE ?= upgrades-package-tests:8.5.12
RUN = docker run --rm -e COMPOSER_HOME=/tmp/composer -e COMPOSER_AUTH --user $$(id -u):$$(id -g) -v "$$(pwd):/app" -w /app
build:
	docker build -f .docker/Dockerfile -t $(IMAGE) .
install:
	$(RUN) $(IMAGE) composer install --no-interaction --no-progress --no-plugins --no-scripts --prefer-dist
lint:
	$(RUN) --network none $(IMAGE) php tools/lint.php
test:
	$(RUN) --network none $(IMAGE) php vendor/bin/phpunit $(ARGS)
validate:
	$(RUN) --network none $(IMAGE) composer validate --no-check-publish
audit:
	$(RUN) $(IMAGE) composer audit --locked
check: lint test validate audit
