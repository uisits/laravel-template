# UIS ITS Laravel Template

This is a template application for UIS ITS applications built using laravel framework.

## Features
- User Authentication using [UIS ITS Laravel oidc](https://github.com/uisits/laravel-oidc) package.
- Migrations
- Databases: MySQL, Oracle
- AD-LDAP
- User Management
- User Role Management using [Spatie Permissions](https://spatie.be/docs/laravel-permission/v6/introduction)
- User Feedbacks
- Laravel Telescope for development

## Usage
- To use this package as a starter template simply run the command
    ```shell
    composer create-project uisits/starter project_name
    ```
  OR
   ```shell
    composer create-project uisits/starter:^0.0.1 project_name
    ```

- To create a database during project scaffolding you can add your database connection details in the `.env` file.
    Then you can simply run:
    ```shell
    php artisan migrate
    ```
    This will ask you if you want to create a database and you can pass `y` flag.
    > Note:
    > 
    > In case you want to create the database as a part of shell script you want to pass the `--force` flag to the above command.
    > 
    > ```shell
    > php artisan migrate --force 
    > ```

