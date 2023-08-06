# Zefix Validator

## Setup

### Database & Queue

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=33072
DB_DATABASE=check_zefix
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
```

### Zefix

```
ZEFIX_USERNAME=
ZEFIX_PASSWORD=
```

## Import

### Command

```
php artisan companies:import

Heading Row
kreditoren_nr,name,mwst_nr_clean

Path File
public storage 'Documents-20230805-091205.xlsx'
```

## Validate

### Command

```
php artisan companies:validate
```

### Validations

```
- check_valid_uid // required|regex:/^CHE-[0-9]{3}\.[0-9]{3}\.[0-9]{3}$/
- check_account_number_duplicate
- check_account_number_duplicate_count
- check_uid_duplicate
- check_uid_duplicate_count

```

## Zefix

### Command

```
php artisan companies:zefix

php artisan queue:listen
```

