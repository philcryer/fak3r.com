SSH_CONNECTION=linuxuser@hector:docker/fak3r.com/html
BUILD_OUTPUT=dist

define get_hash
	git log -1 --pretty=format:%h > .current_build
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
	@$(call get_hash)
	npm run build

build-verbose:
	@$(call get_hash)
	npm run build -- --verbose

prod:
	@$(call get_hash)
	npm run prettier
	npm run build
	rsync -aP ${BUILD_OUTPUT}/ ${SSH_DETAILS}

deploy:
	rsync -aP ${BUILD_OUTPUT}/ ${SSH_DETAILS}



