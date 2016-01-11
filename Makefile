PROJECT=fak3r.com

DEV_DIR=devel
DEV_URL=0.0.0.0
TMP_DIR=/tmp/new-site
PROD_DIR=/var/www/${PROJECT}
DATE_NOW=`date +'%Y-%m-%d.%H:%M'`
OLD_DIR=/tmp/${PROJECT}-old-${DATE_NOW}

all: $(TARGETS)

dev: all
	@echo "*** Beginning dev build of ${PROJECT} at $(DATE_NOW)"
	hugo --baseUrl=${DEV_URL} --buildDrafts=false --buildFuture=false --destination=${DEV_DIR} --disableRSS=true --disableSitemap=true --watch

prod: all
	@echo "*** Beginning prod deploy of ${PROJECT} at $(DATE_NOW)"
	if [ ! -d ${PROD_DIR} ]; then mkdir ${PROD_DIR}; fi
	hugo -d ${TMP_DIR}
	mkdir -p ${OLD_DIR}
	mv -f ${PROD_DIR}/* ${OLD_DIR}
	mv -f ${TMP_DIR}/* ${PROD_DIR}

clean: all
	@echo "*** Cleaning directories: ${DEV_DIR} ${OLD_DIR}*"
	rm -rf ${DEV_DIR}
	rm -rf ${TMP_DIR}
	rm -rf ${OLD_DIR}*
