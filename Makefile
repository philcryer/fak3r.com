list:
	@echo "All commands:"
	@echo "  - install: install all npm packages"
	@echo "  - dev: run astro dev server"
	@echo "  - build: build astro project"
	@echo "  - build-verbose: build astro project with debug"
	@echo "  - build-prod: build astro project for prod with prettier"
	@echo "  - build-prod-deploy: build astro project for prod with prettier and deploy"
	@echo "  - deploy: deploy built astro project"

install:
	npm install
	npm audit fix

dev:
	npm run dev

build:
	npm run build

build-verbose:
	npm run build -- --verbose

build-prod:
	npm run prettier
	npm run build

