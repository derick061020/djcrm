<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Concerns\HasActions;
use Filament\Support\Concerns\HasForms;

class Leads extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.leads';

    protected static ?int $navigationSort = 1;

    protected function getLayoutWidth(): string
    {
        return 'full';
    }
}
