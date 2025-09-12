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
	npm audit fix --force

dev:
	npm run dev

build:
	npm run build

build-verbose:
	npm run build -- --verbose

build-prod:
#	build_hash_short=$(git rev-parse --short HEAD);
#	build_hash_full=$(git rev-parse HEAD)
#	cp src/components/Footer.astro.dist src/components/Footer.astro
#	sed -i "s/BUILD_HASH_FULL/${git rev-parse --short HEAD}/g" src/components/Footer.astro
#	sed -i "s/BUILD_HASH_SHORT/${build_hash_short}/g" src/components/Footer.astro
	npm run prettier
	npm run build
#	cp src/components/Footer.astro.dist src/components/Footer.astro

build-prod-deploy:
#	build_hash_short=$(git rev-parse --short HEAD); build_hash_full=$(git rev-parse HEAD)
#	cp src/components/Footer.astro.dist src/components/Footer.astro
#	sed -i "s/BUILD_HASH_FULL/$build_hash_full/g" src/components/Footer.astro
#	sed -i "s/BUILD_HASH_SHORT/$build_hash_short/g" src/components/Footer.astro
	npm run prettier
	npm run build
#	cp src/components/Footer.astro.dist src/components/Footer.astro
	rsync -aP dist/ linuxuser@hector:docker/fak3r.com/html

deploy:
	rsync -aP dist/ linuxuser@hector:docker/fak3r.com/html
