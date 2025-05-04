<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class Chat extends Component
{
    protected string $view = 'forms.components.chat';

    public static function make(): static
    {
        return app(static::class);
    }
    public function sendMessage()
    {
        return true;
    }


}
