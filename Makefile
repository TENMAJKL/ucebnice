install:
	cp .env.example .env
	cp years.txt.example years.txt
	composer install
	yarn
	yarn run mix
