.PHONY:install
install: composer.phar
	php composer.phar install

composer.phar:
	curl -sS https://getcomposer.org/installer | php -- --filename=composer.phar
	chmod +x composer.phar
