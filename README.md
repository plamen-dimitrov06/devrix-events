# devrix-events
Wordpress plugin for basic event organization.

## Environment

Docker with wordpress and mariadb containers.

`docker-compose up -d` - start the project.

## Setup

Complete the initial wordpress installation and activate the plugin.
You can add a new Event using the navigation in the WP admin.
The archive page is available at `?post_type=event`.

## Considerations

* PSR-4 Namespaces - In order to stick to the wordpress coding standards, I've decided not to use PSR-4 Autoloading. Since PSR-4 is `cammelCase` and Wordspress is `snake_case`, that would create inconsistent naming, resulting in poor readability and developer experience.

* Archive page - I couldn't get default archive-{custom post type}.php logic to work. `/events` kept returning 404.