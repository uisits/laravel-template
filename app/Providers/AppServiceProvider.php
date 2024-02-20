<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        CreateAction::configureUsing(function ($action) {
            return $action->slideOver();
        });
         */
        RichEditor::configureUsing(function($editor){
            $editor->toolbarButtons([
                'attachFiles',
                'blockquote',
                'bold',
                'bulletList',
                'codeBlock',
                'h2',
                'h3',
                'html',
                'italic',
                'link',
                'orderedList',
                'redo',
                'strike',
                'underline',
                'undo'
            ]);

            $editor->disableToolbarButtons([
                //'blockquote',
                //'strike',
            ]);
        });

        if(Schema::hasTable('settings'))
        {
            $settings = DB::table('settings')->first();
            config(['application.settings' => $settings]);
        }

    }
}
