.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' ${MAKEFILE_LIST}

.DEFAULT_GOAL := help

up: ## Up docker containers with app
	docker-compose up -d
	@echo "\n>>> Open http://localhost:9088 in your browser <<<\n"

down: ## Down containers
	docker-compose down

install: ## Setup app before use it
	docker-compose build
	composer install
	if [ ! -f .env ] ; then \
		cp .env.example .env \
	; fi
	@echo "\n.env file created.\nProject ready to start. Type 'make up' to build and start use."
