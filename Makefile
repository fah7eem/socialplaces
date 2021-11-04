install: compose migrate

compose:
	@echo "Installing Composer Libraries"
	composer install

migrate:
	@echo "Migrating DB"
	./tools/migratedb

serve:
	@php -S localhost:8081 -t public/