# Composer Lock Check

Check the composer lock dist urls for patterns.
If the pattern occurs, throw an error.

# Usage

Install via composer
```sh
composer require arjeantonio/composer-lock-check
```

# Configuration

By default the plugin checks for Wordpress packages not having a version.

Create your own pattern by providing an additional pattern:
```sh
    "extra": {
        "wordpress-plugin-patterns": [
            {
                "pattern": "https://downloads.wordpress.org/plugin/[a-zA-Z0-9-]+.zip?timestamp=\\d+",
                "description": "WordPress plugin default pattern",
                "error_message": "WordPress plugin found in the composer.lock file using timestamp versioning!"
            }
        ]
    }
```