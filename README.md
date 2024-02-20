composer require filament/filament:"^3.2" -Wcomposer require filament/filament:"^3.2" -W  
php artisan filament:install --panels

vim app/Providers/Filament/AdminPanelProvider.php  //Rename AppPanelProvider
->id('app')
->path('/')
'primary' => Color::Indigo,
'gray' => Color::Slate,
public function register(): void


mv AdminPanelProvider.php AppPanelProvider.php
 vim config/app.php
vim ./bootstrap/cache/services.php

vim routes/web.php   //empty it

php artisan make:filament-user    //create user
https://apps.uis.edu/dev/login

Publish Configuration:
php artisan vendor:publish --tag=filament-config

INFO  Success! tllos1@uis.edu may now log in at https://apps.uis.edu/dev/login.

npm install
npm run build

vim .env
:1,$ s/MIX/VITE/g

routes/web.php

//LIVEWIRE
Livewire::setScriptRoute(function($handle) {
	            return Route::get('/' . env('VITE_APP_NAME') . '/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function($handle) {
	            return Route::get('/' . env('VITE_APP_NAME') . '/livewire/update', $handle);
});

vim vite.config.js
 { refreshPaths } 


//Creates all the CRUD
php artisan make:filament-resource Application --generate
php artisan make:filament-resource Reference --generate
php artisan make:filament-resource Settings --generate

php artisan make:filament-resource User --generate

//The resources contain the routes and the model it uses
vim app/Filament/Resources/ApplicationResource.php


Authorization for the panel in Production:
https://filamentphp.com/docs/3.x/panels/users
app/Models/User.php implements FilamentUser
#############################################################
Problem 1
    - filament/filament[v3.2.0, ..., v3.2.14] require illuminate/console ^10.0 -> found illuminate/console[v10.0.0, ..., v10.42.0] but these were not loaded, likely because it conflicts with another require.
    - Root composer.json requires filament/filament ^3.2 -> satisfiable by filament/filament[v3.2.0, ..., v3.2.14].


"require": {
        "php": "^8.0.2",
        "guzzlehttp/guzzle": "^7.3",
        "laravel/framework": "^10.3",
        "laravel/sanctum": "^3.0",
        "laravel/tinker": "^2.9"
    },
    "require-dev": {
        "fakerphp/faker": "^1.9.1",
        "jasonmccreary/laravel-test-assertions": "^2.3",
        "laravel-shift/blueprint": "^2.5",
        "laravel/pint": "^1.0",
        "laravel/sail": "^1.0.1",
        "mockery/mockery": "^1.4.4",
        "nunomaduro/collision": "^6.1",
        "phpunit/phpunit": "^9.6",
        "spatie/laravel-ignition": "^2.0"
    },

#############################################################
cp .vimrc ~

curl -Lo phpactor.phar https://github.com/phpactor/phpactor/releases/latest/download/phpactor.phar
chmod a+x phpactor.phar
mv phpactor.phar /usr/local/bin/phpactor
phpactor status

apk add py3-pip
pip3 install pynvim

curl -fLo ~/.vim/autoload/plug.vim --create-dirs https://raw.githubusercontent.com/junegunn/vim-plug/master/plug.vim

:PlugInstall
:CocInstall coc-phpls
:CocInstall coc-json
:CocInstall coc-html
:CocInstall coc-css
:CocInstall coc-tailwind

#############################################################

App\Models\Reference::factory()->count(5)->create()


#############################################################
Enums:
They go in the Model and on the Filament Resource

#############################################################
App\Models\Application::factory()->create()
App\Models\Reference::factory()->count(3)->create()

App\Models\Application::factory()->state(['uin' => '668123444'])->create()
App\Models\Reference::factory()->state(['application_id' => 9])->count(3)->create()

Settings::factory()->count(1)->create();

php artisan migrate:refresh --seed

php artisan telescope:clear

User::find(1)->hasRole('admin');

#############################################################
php artisan migrate:refresh --seed
Reference::factory()->count(5)->create();


git filter-branch -f --index-filter 'git rm --cached --ignore-unmatch database/database.sqlite'


#############################################################
Widget:
php artisan make:filament-widget
WelcomeWidget
SKIP Resource
Custom
The [app] paneL

php artisan about
php artisan optimize

