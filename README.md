# PHP Prophetic Framework

Congratulations on cloning Prophetic framework. This guide aims to help you familiarize with the framework and guide you through basic operations. Any further difficulties should be emailed to [me](prosperpepple12@gmail.com)

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

## Instructions for Use

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
