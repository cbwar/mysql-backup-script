.PHONY:install update clean
.DEFAULT_GOAL=install

ENV?=dev
PHP=php
COMPOSER=./composer.phar
CURL=/usr/bin/curl

install:
	$(CURL) -sS https://getcomposer.org/installer | php -- --filename=$(COMPOSER)
	chmod +x $(COMPOSER)
	$(COMPOSER) install $(COMPOSER_OPTS)

update:
	$(COMPOSER) update $(COMPOSER_OPTS)

clean:
	rm -f composer.phar
