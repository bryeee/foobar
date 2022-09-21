
## Installation

```bash
composer install
cp .env.example .env

*note: update environment variables first 
before you proceed to next step*

php artisan migrate
php artisan key:generate
php artisan jwt:secret
```
    
## Environment Variables

To run this project, you will need to update the following environment variables to your .env file

*Database related environment variables*

`DB_CONNECTION=mysql`

`DB_HOST=127.0.0.1`

`DB_PORT=3306`

`DB_DATABASE=laravel`

`DB_USERNAME=root`

`DB_PASSWORD=`

*Make sure the cache driver value is "redis"*

`CACHE_DRIVER=redis`

*Redis related environment variables*

`REDIS_HOST=127.0.0.1`

`REDIS_PASSWORD=null`

`REDIS_PORT=6379`
## API Reference

### Register

```http
  POST /api/user/register
```

| Request Headers                 | 
| :--------                       |
| `Accept: application/json`      | 

*body raw json*
```
{
    "email": "foobar@yopmail.com",
    "password": "123456",
    "password_confirmation": "123456"
}
```

| Parameter                 | Type     |
| :--------                 | :------- |
| `email`                   | `string` |
| `password`                | `string` |
| `password_confirmation`   | `string` |

### Login

```http
  POST /api/user/login
```

| Request Headers                 | 
| :--------                       |
| `Accept: application/json`      |

*body raw json*
```
{
    "email": "foobar@yopmail.com",
    "password": "123456",
}
```

| Parameter                 | Type     |
| :--------                 | :------- |
| `email`                   | `string` |
| `password`                | `string` |

### Get GitHub Users

```http
  POST /api/github/users
```

| Request Headers                 | 
| :--------                       |
| `Accept: application/json`      | 
| `Authorization: Bearer {token}` | 

*body raw json*
```
{
    "usernames": ["foo", "bar"]
}
```

| Parameter                 | Type     |
| :--------                 | :------- |
| `usernames`               | `array`  |

### Logout

```http
  POST /api/user/logout
```

| Request Headers                 | 
| :--------                       |
| `Accept: application/json`      | 
| `Authorization: Bearer {token}` | 

### Bonus Challenge

```http
  GET /api/hamming-distance?foo=1&bar=4
```
