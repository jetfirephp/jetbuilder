# JetBuilder CMS

A simple CMS

### Getting started

1. PHP 5.6+ is required
2. NPM is required
3. Composer is required

### Installation

Via [composer](https://getcomposer.org)

```bash
$ composer install
```

Via [npm](https://www.npmjs.com)

```bash
$ npm install
```

### Setup

All you have to do is to rename the `/config/setting.inc.json.sample` to `/config/setting.inc.json` and to replace by your values.

You have to change the permission for `/storage` and `/public` folder (755 or 777)

And run the following commands to create your database tables and to load some data: 

```bash
 $ php jet migrations:migrate
```

### Usage in dev

If you want to use this platform in dev mode then run this command :
 
```bash
 $ npm run dev --proxy_target={app_url}
```
 
### Usage in prod

```bash
 $ npm run build --public_path={public_path}
```