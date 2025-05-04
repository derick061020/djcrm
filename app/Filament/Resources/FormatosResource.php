<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormatosResource\Pages;
use App\Filament\Resources\FormatosResource\RelationManagers;
use App\Models\Formatos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormatosResource extends Resource
{
    protected static ?string $model = Formatos::class;

    protected static ?string $navigationGroup = 'Sistema';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin') || auth()->user()->hasRole('sales_operator');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Página 1')
                            ->schema([
                                Forms\Components\FileUpload::make('data.image_page_1')
                                    ->label('Imagen')
                                    ->image()
                                    ->required(),
                                Forms\Components\TextInput::make('data.title_page_1')
                                    ->label('Título')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('data.slogan_page_1')
                                    ->label('Lema')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Section::make('Página 3')
                            ->schema([
                                Forms\Components\Textarea::make('data.text_page_3')
                                    ->label('Texto')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Página 4')
                            ->schema([
                                Forms\Components\ColorPicker::make('data.color_4_background')
                                    ->label('Color de Fondo')
                                    ->default('#2e3221')
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Página 5')
                            ->schema([
                                Forms\Components\TextInput::make('data.h2_page_5')
                                    ->label('Título H2')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('data.h1_page_5')
                                    ->label('Título H1')
                                    ->required()
                                    ->maxLength(255),
                                
                            ]),

                        
                    ])
                    ->columnSpan('full'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormatos::route('/'),
            'create' => Pages\CreateFormatos::route('/create'),
            'edit' => Pages\EditFormatos::route('/{record}/edit'),
        ];
    }
}
