# devrix-events
Wordpress plugin for basic event organization.

## Environment

Docker with wordpress and mariadb containers.

`docker-compose up -d` - start the project.

## Setup

Complete the initial wordpress installation and activate the plugin.

## Considerations

PSR-4 Namespaces - In order to stick to the wordpress coding standards, I've decided not to use PSR-4 Autoloading. Since PSR-4 is `cammelCase` and Wordspress is `snake_case`, that would create inconsistent naming, resulting in poor readability and developer experience.