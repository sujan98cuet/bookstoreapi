COMPOSER_BIN=$(shell which composer 2> /dev/null || echo './composer.phar')
check-composer:
	if [ "$(COMPOSER_BIN)" = "./composer.phar" ] ; then $(MAKE) composer.phar; fi

composer.phar: INSTALLER_HASH='$(shell curl -s https://composer.github.io/installer.sig)'
composer.phar: INSTALLER_TMP='/tmp/composer-setup.php'
composer.phar:
	php -r "copy('https://getcomposer.org/installer', ${INSTALLER_TMP});"
	php -r "if (hash_file('SHA384', ${INSTALLER_TMP}) <> ${INSTALLER_HASH}) \
                { echo 'Failed to match hash {INSTALLER_HASH}. Installer corrupt' . PHP_EOL; unlink(${INSTALLER_TMP});}"
	 php ${INSTALLER_TMP}
	unlink ${INSTALLER_TMP}
