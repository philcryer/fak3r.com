BUILD_OUTPUT=dist
BUILD_STATUS=$(git status --porcelain | wc -l)
SSH_CONNECTION=linuxuser@hector:docker/fak3r.com/html

define git-hash
	git log -1 --pretty=format:%h > .current_build
endef

define code-deploy
	echo "Deploying code from: ${BUILD_OUTPUT}/"
	rsync -aP ${BUILD_OUTPUT}/ ${SSH_DETAILS}
endef

list:
	@echo "All commands:"
	@echo "  - install: install all required npm packages"
	@echo "  - dev: run the astro dev server locally and refresh when files are changed"
	@echo "  - build: build astro project"
	@echo "  - build-verbose: build astro project with debug settings on"
	@echo "  - prod: build astro project with prettier, deploy code from dist/ to remote server"
	@echo "  - deploy: deploy code from dist/ to remote server"

install:
	npm install
	npm audit fix
	npm audit fix --force

dev:
	npm run dev

build:
	@$(call git-hash)
	npm run build

build-verbose:
	@$(call git-hash)
	npm run build -- --verbose

prod:
	@$(call git-hash)
	npm run prettier
	npm run build
	@$(call code-deploy)

deploy:
	@$(call code-deploy)
