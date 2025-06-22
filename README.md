# Symfony xPath Parser

```bash
   docker compose up -d
```
```bash
   docker compose exec php bash
```
```bash
   composer install
```
```bash
   php bin/console doctrine:schema:update --force
```
```bash
   php bin/console app:parse-products
```
```bash
   { php -S 0.0.0.0:8000 -t public; php bin/console messenger:consume async -vv; } &
```
