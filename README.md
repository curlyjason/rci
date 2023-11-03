# Docker Setup Notes
## After Cloning the Repo
- Run `composer install --ignore-platform-reqs` to get all the dependencies in place
- Run `docker ps` to see current services and ports to avoid
- Run `bin/cake EditYaml [options]`
    - Provide a short project name (2-3 letters) `-d {database port} -w {web port}`
    - The short project name, database port, and web port keep this particular docker container from stepping on other docker containers
- Run `docker compose up --build -d`
- Run `bin/db_setup.sh`
- Run `bin/migrations.sh`
### Php Interpretor setup in PhpStorm
- Go to the menu `PhpStorm/Preferences...`
- Choose `> PHP`
- Set `PHP language level` as appropriate
- Choose the three dots next to the `CLI interpreter` choice
- Click `+` at the top of the left sidebar
- Select from `From Docker, Vagrant, VM, WSL, Remote...` (it will be the top, highlighted choice)
- Choose the `Docker Compose` radio button
- Choose the name of the PHP container created for this project from the `Service` dropdown and press the `OK` button to dismiss the dialog box
- In the `General` section press the 're-spin' icon for `PHP executable`
- `Apply` / `OK` your way back to the main Preferences dialog
- The preferences `PHP` pane, `Path mappings:` should include the path `<Project root>→/application;` for PhpUnit configuration to work
### PhpUnit Configuration in PhpStorm
- Click `>` to expand the `PHP` preferences sub menu
- Choose `Test Frameworks`
- Choose `+` at the top left sidebar of the `PHP > Test Frameworks` pane
- Choose `PhpUnit Local`
- Choose `Use Composer autoloader` radio button
- Click the `Path to script:` folder icon and select `vendor/autoload.php`
- In the `Test Runner` section, check `Default configuration file:` checkbox, then click its folder icon and select `phpunit.xml.dist`
- `Apply` / `OK` your way back to the editor


# CakePHP Application Skeleton

![Build Status](https://github.com/cakephp/app/actions/workflows/ci.yml/badge.svg?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%207-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

A skeleton for creating applications with [CakePHP](https://cakephp.org) 5.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist cakephp/app
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist cakephp/app myapp
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

## Update

Since this skeleton is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades, so you have to do any updates manually.

## Configuration

Read and edit the environment specific `config/app_local.php` and set up the
`'Datasources'` and any other configuration relevant for your application.
Other environment agnostic settings can be changed in `config/app.php`.

## Layout

The app skeleton uses [Milligram](https://milligram.io/) (v1.3) minimalist CSS
framework by default. You can, however, replace it with any other library or
custom styles.
