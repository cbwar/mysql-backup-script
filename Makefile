.PHONY:install ide
.DEFAULT_GOAL=install

ENV?=dev
PHP=php
COMPOSER=./composer.phar
CURL=/usr/bin/curl
COMPOSER_OPTS=

ifeq ($(ENV), dev)
install: vendor
endif
ifeq ($(ENV), prod)
COMPOSER_OPTS=--no-dev --optimize-autoloader --prefer-dist
install: vendor
endif

$(COMPOSER):
	$(CURL) -sS https://getcomposer.org/installer | php -- --filename=$(COMPOSER)
	chmod +x $(COMPOSER)

vendor: $(COMPOSER) composer.lock
	$(COMPOSER) install $(COMPOSER_OPTS)

composer.lock: composer.json
	$(COMPOSER) update $(COMPOSER_OPTS)

clean:
	rm -f composer.phar
