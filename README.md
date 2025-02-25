# Prophetic: The PHP Preacher

Congratulations on cloning Prophetic framework. This documentation aims to help you understand the framework structure and help you customize it to your project. Any further difficulties should be emailed to [me](mailto:prosperpepple12@gmail.com).

Prophetic framework is designed to quickly setup your project by installing some recommended and packages and directory structure.

## Directory Structure

Below is a summary of directories and their contents:

- .vscode: For storing user generated workspace settings on the vscode editor.

- cache: For storing Twig caches, effective for production.

- configs: Stores configuration files for doctrine, di-container, define routes and constants.

- dev: Holds test files for quick testing.

- migration: Stores versions of doctrine's database migrations.

- node_modules: Stores npm packages.

- public: Contains all public files rendered on the web browser.

- resources: Stores all the frontend components and twig templates.

- src: Contains the major application programs including the 'interfaces', 'controllers', 'entities', etc.

- tests: Contains the unit/integration tests for the project.

- .env.example: Environment variables for the project.

- .gitignore: Gitignore dependency files like 'vendor' and 'node_modules' directory and sensitive files like '.env'.

- .htaccess: (For Apache web servers), redirects all requests to the 'public/index.php' script.

- bootstrap.php: Loads the '.env' files and instantiates the container.

- composer.json: Holds a list of dependency packages to be installed by composer.

- package.json: Holds a list of dependency packages to be installed by npm.

- phpunit.xml: Configuration file for PHPUnit.

- prophetic: Initiation file for customized console commands.

- robots.txt: Contains basic web search engine rules.

- webpack.config.js: Configures the Symfony Webpack encore dependency.

## Project Setup

### Installing Dependencies

- When the package has been cloned, begin by running the following terminal command to install all composer and npm dependencies:

```cmd
composer require
npm install
```

- After loading the packages, you'll want to run the following command to bundle your web resources:

```cmd
npm run dev
```

### Changing Project Name

- To change the name of the project run the following terminal command:

```cmd
php prophetic app:change-project-name <new-project-name>
```

## Creating Entities

By default, Prophetic uses Doctrine ORM for mapping. After installing dependencies and modifying the name of the project to your taste, you'll want to start creating entities. this can be done within the `src/entities` directory. All entities must follow the rules according to the Symfony's Doctrine ORM Guides ([See documentation](https://doctrine-project.org/projects/doctrine-orm/en/3.3/index.html)).
After Entities have been created, it can then be migrated by the terminal command:

```cmd
php prophetic diff
php prophetic migrations:migrate
```

## Creating Commands

To add your custom commands, you can create command class file within the `src/commands` directory. This class must extend the `Symfony\Component\Console\Command\Command` class. After creating the command according to the [Symfony\Console guidelines](https://symfony.com/doc/current/console.html#creating-a-command).
After creating the command, it can be registered within the `custom_commands.php` file in the `configs/console` directory, by adding the fully qualified classname of your custom command within the array to be passed to the `prophetic` command script within the root directory.
Please while creating commands, it is best to name it with a prefix `app:<commandName>` to easily differentiate between custom commands and built-in commands.

## Adding Container Bindings

Prophetic uses DI container to automatically resolve classes. To add custom bindings to the project, it can be done within the `configs/container/container_bindings.php` file. [See the documentation](https://php-di.org/docs) on how to add a class definition.

## Migration Configuration

The `configs/db/migrations.php` file contains configuration options for the Doctrine Migration.

## Defining Routes

Prophetic runs on the SlimPHP Framework and needs routes to be defined for each URI to be resolved properly. To define routes, the `configs/routes/web.php` file is available. Simply define the route like so within the function block:

```php
$app-><request method e.g. get, post>('<route name as string>', [ControllerName::class, fn(){}, 'arguments (optional)']);
```

The Controller classes are defined in the `src/controllers` directory.

## Application Configurations

The main application configuration file is within the `configs/app.php` file, please go through to configure as needed and feel free to add custom configurations. Within the code, the `Src\Services\ConfigService::class` can be injected and the `$config->get()` method recursively tries to resolve the configuration option of choice. Example:

```php
// Within the configs/app.php

'config_name' => [
  'config_option_1' => 'config_value_1',
  'config_option_2' => 'config_value_2'
]

// Within a calling class

$config->get('configName.config_option_1');
```

## Adding SlimPHP Middlewares

Middlewares are like a piece of code that runs together with the application on each requests ([See the documentation](https://slimframework.com/docs/v4/concepts/middleware.html)). They can be created within the `src/middlewares` directory and registered in Prophetic within the `configs/middleware.php`.

## Path Constants

Some constants are created to easily refer to specific files/directories in the code. They can be found within the `configs/path_constants.php` file.

## Frontend and Resource Bundling

All frontend code is found within the `resources` directory in their respective folders. Feel free to create more folders as you wish. Please remember all `.css` file created should be imported by any `.scss` file and all `.scss` file should be imported by any `.js` file. All `.js` files are to be registered within the `webpack.config.js` in the entry config section e.g.:

```js
.addEntry("name", "./resources/js/jsFile.js")
```

## Writing Tests

Prophetic Tests can be written within the `tests` directory. By default, Prophetic uses PHPUnit as its testing framework ([See documentation](https://docs.phpunit.de/en/11.5/)). The configuration file is within the `phpunit.xml` file in the root directory (alter to your taste).
All unit tests are written within the `tests/unit` directory and integration tests within the `tests/integration` directory, modify as needed.
