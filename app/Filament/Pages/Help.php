<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Help extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.help';

    protected static string | \UnitEnum | null $navigationGroup = 'Help';
}
