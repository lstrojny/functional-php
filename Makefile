install:
	composer install

clean:
	rm -rf vendor

test:
	vendor/bin/phpunit tests/

.PHONY: test
