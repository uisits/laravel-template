# UIS ITS - Laravel Template

This is a template application that provides a starting point for application development using Laravel at UIS ITS.

# Stack
- Laravel 11.x
- Filament 3.x

# Features
- User and Role management
- Settings
- [Laravel Debugbar](http://laraveldebugbar.com/)
- [Laravel Telescope](https://laravel.com/docs/11.x/telescope)
- [Laravel Pulse](https://laravel.com/docs/11.x/pulse)
- [Laravel Horizon](https://laravel.com/docs/11.x/horizon)
- [Tighten Duster](https://github.com/tighten/duster) for liniting.
- Github Actions via Tighten Duster to automatically lint on code push.
- [Laravel app health](https://spatie.be/docs/laravel-health/v1/introduction)


# Setting up for new project.
- From the GitHub home page for the repository click on "Use this template" button to clone and create a new repository.
- Then from the newly created repository clone the project.
- Run `composer install && npm install`
- Setup your `.env` file.
- Run `php artisan migrate`
- Run `npm run build`
- The application should now be live to preview on the browser.
