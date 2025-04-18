# Task Manager API

## How to Use

1. Clone the repo
2. Run `docker-compose up -d`
3. Visit `http://localhost/api/documentation` for Swagger UI

### Test User
- Email: test@example.com
- Password: password

You can register new users or login with the test user and manage tasks.

## Development Setup
The `composer install` command is currently run inside the `entrypoint.sh` script. This ensures that all dependencies are installed inside the container every time it's started if the `vendor/` directory is missing.
However, this does mean there's a slight delay between the container starting and the application being fully ready to serve requests (due to Composer installing dependencies).

For faster container startup during development, you may pre-install dependencies on your host machine with:

    composer install