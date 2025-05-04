<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientesResource\Pages;
use App\Filament\Resources\ClientesResource\RelationManagers;
use App\Models\Clientes;
use App\Models\Formatos;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View as ViewComponent;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientesResource extends Resource
{
    protected static ?string $model = Clientes::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Actions::make([
                    Actions\Action::make('contactar')
                        ->label('Contactar')
                        ->icon('heroicon-o-phone')
                        ->action(function (Clientes $record) {
                            return redirect()->away('tel:+' . $record->contacto, '_blank');
                        }),
                    Actions\Action::make('whatsapp')
                        ->label('WhatsApp')
                        ->color('success')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->modalWidth('md')
                        ->slideOver()
                        ->extraModalWindowAttributes([
                            'class' => 'fixed inset-0 bg-transparent',
                        ])
                        ->modalContent(function (Clientes $record) {
                            // Get messages from WhatsApp API
                            $whatsappService = app(\App\Services\WhatsAppService::class);
                            $messages = $whatsappService->getMessages($record->contacto);
                            
                            return view(
                                'filament.pages.whatsapp-chat',
                                [
                                    'record' => $record,
                                    'messages' => $messages,
                                ]
                            );
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->closeModalByClickingAway(false)
                        ->action(function (Clientes $record) {
                            // La acción se maneja en el modal
                        }),
                    Actions\Action::make('aprobar')
                        ->label('Aprobar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            Select::make('formato_evento')
                                ->label('Formato Seleccionado')
                                ->options(Formatos::all()->pluck('name', 'id'))
                                ->required(),
                            Textarea::make('observaciones')
                                ->label('Comentario de Aprobación'),
                        ])
                        ->action(function (array $data, Clientes $record) {
                            $record->update([
                                'estado' => true,
                                'formato_evento' => $data['formato_evento'],
                                'observaciones_operador' => $record->observaciones_operador . "\n\n" . $data['observaciones'] . "\n\nAprobado por " . auth()->user()->name . " el " . now()->format('d/m/Y H:i'),
                                'aprobado_por_id' => auth()->id(),
                                'aprobado_at' => now(),
                            ]);
                            redirect('/crm/clientes/' . $record->id.'/edit');
                        })
                        ->visible(function (Clientes $record) {
                            return !$record->estado;
                        }),
                    
                    Actions\Action::make('asignar_dj')
                        ->label('Agendar evento')
                        ->icon('heroicon-o-calendar')
                        ->form([
                            Select::make('dj_id')
                                ->label('Seleccionar DJ')
                                ->options(\App\Models\User::where('role', 'dj')->pluck('name', 'id'))
                                ->default(function (Clientes $record) {
                                    return $record->dj_id;
                                })
                                ->required(),
                        ])
                        ->action(function (array $data, Clientes $record) {
                            $record->update([
                                'dj_id' => $data['dj_id'],
                                'agendado' => true,
                                'agendado_por_id' => auth()->id(),
                                'agendado_at' => now(),
                            ]);

                            // Find Carlos and notify him
                            $user = \App\Models\User::where('name', 'Carlos')->first();
                            if ($user) {
                                Notification::make()
                                    ->title('Nuevo Trabajo Asignado')
                                    ->body("Se te ha asignado un nuevo trabajo para el cliente: {$record->nombre}")
                                    ->success()
                                    ->actions([
                                        Action::make('ver_detalles')
                                            ->label('Ver Detalles')
                                            ->url('/crm/clientes/' . $record->id),
                                    ])
                                    ->sendToDatabase($user);
                            }
                        })
                        ->visible(function (Clientes $record) {
                            return !$record->agendado && !auth()->user()->hasRole('sales_operator');
                        }),
                    Actions\Action::make('aceptar_contrato')
                        ->label('Aceptar Contrato')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (array $data, Clientes $record) {
                            $record->update([
                                'contract_accepted' => true,
                                'contract_accepted_at' => now(),
                            ]);
                        })
                        ->visible(function (Clientes $record) {
                            return $record->estado && $record->concretada && !$record->contract_accepted;
                        }),
                ])->fullWidth()
                ->hiddenOn('create'),

                // Chat WhatsApp
                Grid::make(3)->schema([
                    Forms\Components\Tabs::make('Tabs')
                        ->columnSpan(2)
                        ->tabs([
                            Forms\Components\Tabs\Tab::make('Información Básica')
                                ->schema([
                                    TextInput::make('nombre')
                                        ->required()
                                        ->maxLength(255)
                                        ->label('Nombre del Cliente'),
                                    TextInput::make('contacto')
                                        ->required()
                                        ->maxLength(20)
                                        ->label('Número de Contacto')
                                        ->placeholder('54 300 123 4567')
                                        ->tel()
                                        ->prefix('+'),
                                    Select::make('tipo_evento')
                                        ->required()
                                        ->options([
                                            'boda' => 'Boda',
                                            'cumpleaños' => 'Cumpleaños',
                                            'fiesta_corporativa' => 'Fiesta Corporativa',
                                            'evento_privado' => 'Evento Privado',
                                            'otro' => 'Otro'
                                        ])
                                        ->label('Tipo de Evento'),
                                    DatePicker::make('fecha_estimada')
                                        ->required()
                                        ->label('Fecha Estimada del Evento'),
                                    TextInput::make('cantidad_personas')
                                        ->required()
                                        ->numeric()
                                        ->label('Cantidad de Personas'),
                                ])
                                ->visible(function (?Clientes $record) {
                                    return true; // Siempre visible
                                }),
                            Forms\Components\Tabs\Tab::make('Detalles del Evento')
                                ->schema([
                                    Textarea::make('ubicacion_local')
                                        ->required()
                                        ->columnSpanFull()
                                        ->label('Ubicación del Local'),
                                    TextInput::make('requerimientos')
                                        ->required()
                                        ->label('Requerimientos Específicos'),
                                    TextInput::make('referencias_musicales')
                                        ->required()
                                        ->label('Referencias Musicales'),
                                    TimePicker::make('hora_inicio')
                                        ->required()
                                        ->label('Hora de Inicio'),
                                    TimePicker::make('hora_fin')
                                        ->required()
                                        ->label('Hora de Fin'),
                                ])
                                ->columns(2)
                                ->visible(function (?Clientes $record) {
                                    return true; // Siempre visible
                                }),
                            Forms\Components\Tabs\Tab::make('Panel del Operador')
                                ->schema([
                                    Textarea::make('observaciones_operador')
                                        ->label('Observaciones del Operador')
                                        ->columnSpanFull(),
                                    Select::make('formato_evento')
                                        ->label('Formato Seleccionado')
                                        ->options(Formatos::all()->pluck('name', 'id'))
                                        ->searchable()
                                        ->live()
                                        ->columnSpanFull(),
                                ])
                                ->columns(2)
                                ->hiddenOn('create')
                                ->visible(function (Clientes $record) {
                                    return !auth()->user()->hasRole('data_manager');
                                }),
                            Forms\Components\Tabs\Tab::make('Presupuesto')
                                ->schema([
                                    Toggle::make('iva_incluido')
                                        ->label('Incluir IVA (21%)')
                                        ->default(true)
                                        ->required(),
                                    Forms\Components\Repeater::make('budget_items')
                                        ->label('Items del Presupuesto')
                                        ->columns(5)
                                        ->itemLabel(fn (array $state): ?string => $state['service'] ?? null)
                                        ->schema([
                                            Forms\Components\TextInput::make('service')
                                                ->label('Servicio')
                                                ->required()
                                                ->live(onBlur: true)
                                                ->maxLength(255)->columnSpan(2),
                                            Forms\Components\TextInput::make('unit')
                                                ->label('Unidad')
                                                ->required()
                                                ->numeric()
                                                ->minValue(1)
                                                ->maxLength(50)
                                                ->live(debounce: 500)
                                                ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get,  $state) {
                                                    $unitPrice = $get('unit_price') ?? 0;
                                                    $set('total', $state * $unitPrice);
                                                }),
                                            Forms\Components\TextInput::make('unit_price')
                                                ->label('P.U.')
                                                ->required()
                                                ->numeric()
                                                ->minValue(0)
                                                ->prefix('$')
                                                ->live(debounce: 500)
                                                ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get,  $state) {
                                                    $unit = $get('unit') ?? 1;
                                                    $set('total', $unit * $state);
                                                }),
                                            Forms\Components\TextInput::make('total')
                                                ->label('Total')
                                                ->numeric()
                                                ->prefix('$'),
                                            Forms\Components\Repeater::make('descripciones')
                                                ->label('Descripciones')
                                                ->schema([
                                                    Forms\Components\Textarea::make('descripcion')
                                                        ->label('Descripción')
                                                        ->required()
                                                        ->columnSpanFull(),
                                                ])
                                                ->defaultItems(1)
                                                ->columnSpanFull(),
                                        ])->collapsed()->default([
                                            [
                                                'service'=> 'Servicio de DJ por hora', 
                                                'unit' => 1, 
                                                'unit_price'=> 33 , 
                                                'total' => 33,
                                                'descripciones' => [
                                                    ['descripcion' => 'Servicio de DJ profesional con 2 horas de música en vivo']
                                                ]
                                            ],
                                            [
                                                'service' => 'Sistema de sonido profesional',
                                                'descripciones' => [
                                                    ['descripcion' => 'Equipo de sonido completo con parlantes y subwoofers']
                                                ]
                                            ],
                                            [
                                                'service' => 'Cabina de DJ y mesa de mezclas',
                                                'descripciones' => [
                                                    ['descripcion' => 'Cabina de DJ profesional con mesa de mezclas y controlador']
                                                ]
                                            ],
                                            [
                                                'service'=> 'Servicio de logística y montaje de equipo',
                                                'descripciones' => [
                                                    ['descripcion' => 'Montaje y desmontaje del equipo de sonido y DJ']
                                                ]
                                            ]
                                        ])
                                        ->columnSpanFull(),
                                ])
                                ->visible(function (Clientes $record) {
                                    return $record->estado && !auth()->user()->hasRole('data_manager') && !auth()->user()->hasRole('event_manager');
                                })
                                ->hiddenOn('create'),
                            Forms\Components\Tabs\Tab::make('Estado del Presupuesto')
                                ->schema([
                                    Forms\Components\TextInput::make('budget_link')
                                        ->label('Enlace del Presupuesto')
                                        ->disabled()
                                        ->hiddenOn('create')
                                        ->formatStateUsing(fn(?Clientes $record) => $record && $record->id ? url(route('presupuesto.index', str_pad($record->id, 5, '0', STR_PAD_LEFT))) : null)
                                        ->suffixActions([
                                            Forms\Components\Actions\Action::make('copy')
                                                ->icon('heroicon-s-clipboard-document-check')
                                                ->action(function ($livewire, $state) {
                                                    $livewire->js(
                                                        'window.navigator.clipboard.writeText("'.$state.'");
                                                        $tooltip("'.__('Copied to clipboard').'", { timeout: 1500 });'
                                                    );
                                                }),
                                            Forms\Components\Actions\Action::make('open')
                                                ->icon('heroicon-o-link')
                                                ->action(function ($livewire, $state) {
                                                    $livewire->js(
                                                        'window.open("'.$state.'");'
                                                    );
                                                }),
                                        ]),
                                    Toggle::make('contract_accepted')
                                        ->label('Presupuesto Aceptado')
                                        ->helperText('Indica si el cliente ha aceptado el presupuesto')
                                        ->disabled(),
                                    DatePicker::make('contract_accepted_at')
                                        ->label('Fecha de Aceptación')
                                        ->disabled(),
                                    Toggle::make('alternative_requested')
                                        ->label('Alternativa Solicitada')
                                        ->helperText('Indica si el cliente ha solicitado una alternativa')
                                        ->disabled(),
                                    DatePicker::make('alternative_requested_at')
                                        ->label('Fecha de Solicitud de Alternativa')
                                        ->disabled(),
                                ])
                                ->columnSpanFull()
                                ->visible(function (Clientes $record) {
                                    return $record->estado && !auth()->user()->hasRole('sales_operator') && !auth()->user()->hasRole('event_manager');
                                })
                                ->hiddenOn('create'),
                            ])->extraAttributes(['class' => 'h-full']),
                    ViewComponent::make('filament.pages.whatsapp-chat')->extraAttributes(['class' => 'h-full']),
                ]),

                Section::make('Seguimiento')
                    ->hiddenOn('create')
                                ->schema([
                                    Forms\Components\Repeater::make('seguimiento')
                                        ->label('Llamadas Realizadas')
                                        ->schema([
                                            Forms\Components\DateTimePicker::make('fecha_hora')
                                                ->label('Fecha/Hora')
                                                ->required(),
                                            Forms\Components\Select::make('tipo')
                                                ->label('Tipo de Llamada')
                                                ->options([
                                                    'inicial' => 'Contacto Inicial',
                                                    'followup_1' => 'Primer Follow-up',
                                                    'followup_2' => 'Segundo Follow-up'
                                                ])
                                                ->required()
                                                ->disabled()
                                                ->formatStateUsing(function (?Clientes $record, $state) {
                                                    if (isset($state['fecha_hora'])) {
                                                        $fechaHora = \Carbon\Carbon::parse($state['fecha_hora']);
                                                        
                                                        // Si es la primera llamada
                                                        if (!$record->seguimiento || !isset($record->seguimiento['llamadas'])) {
                                                            return 'inicial';
                                                        }
                                                        
                                                        $ultimaLlamada = collect($record->seguimiento['llamadas'])->sortBy('fecha_hora')->last();
                                                        if ($ultimaLlamada) {
                                                            $ultimaFecha = \Carbon\Carbon::parse($ultimaLlamada['fecha_hora']);
                                                            
                                                            // Si es el primer follow-up (3 días después)
                                                            if ($ultimaLlamada['tipo'] === 'inicial' && $fechaHora->diffInDays($ultimaFecha) >= 3) {
                                                                return 'followup_1';
                                                            }
                                                            
                                                            // Si es el segundo follow-up (72 horas después del primer follow-up)
                                                            if ($ultimaLlamada['tipo'] === 'followup_1' && $fechaHora->diffInHours($ultimaFecha) >= 72) {
                                                                return 'followup_2';
                                                            }
                                                        }
                                                    }
                                                    return 'inicial';
                                                }),
                                            Forms\Components\Textarea::make('observaciones')
                                                ->label('Observaciones')
                                                ->columnSpanFull(),
                                            Forms\Components\Toggle::make('concretada')
                                                ->label('Contratación Concretada')
                                                ->helperText('Marcar si la contratación se concretó en esta llamada')
                                                ->live()
                                                ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get,  $state, Clientes $record) {
                                                    if ($state) {
                                                        $record->update([
                                                            'contract_accepted' => 1,
                                                            'contract_accepted_at' => now(),
                                                        ]);
                                                    }else {
                                                        $record->update([
                                                            'contract_accepted' => 0,
                                                            'contract_accepted_at' => null,
                                                        ]);
                                                    }
                                                }),
                                        ])
                                        ->defaultItems(1)
                                        ->columnSpanFull(),
                                ])
                                ->visible(function (Clientes $record) {
                                    return $record->estado && !auth()->user()->hasRole('data_manager') && !auth()->user()->hasRole('event_manager');
                                }),
                           ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No hay más formularios')
            ->emptyStateDescription('No hay formularios pendientes de revisión')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('contacto')
                    ->label('Contacto')
                    ->searchable(),
                TextColumn::make('tipo_evento')
                    ->label('Tipo de Evento')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),
                TextColumn::make('fecha_estimada')
                    ->label('Fecha Estimada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('cantidad_personas')
                    ->label('Personas')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean()
                    ->sortable()
                    ->color('success'),
                TextColumn::make('agendado_por.name')
                    ->label('Agendado por')
                    ->sortable()
                    ->hidden(function () {
                        return auth()->user()->hasRole('sales_operator');
                    }),
                TextColumn::make('agendado_at')
                    ->label('Fecha de Agenda')
                    ->dateTime()
                    ->sortable()
                    ->hidden(function () {
                        return auth()->user()->hasRole('sales_operator');
                    }),
                TextColumn::make('aprobado_por.name')
                    ->label('Aprobado por')
                    ->sortable()
                    ->hidden(function () {
                        return auth()->user()->hasRole('sales_operator');
                    }),
                TextColumn::make('aprobado_at')
                    ->label('Fecha de Aprobación')
                    ->dateTime()
                    ->sortable()
                    ->hidden(function () {
                        return auth()->user()->hasRole('sales_operator');
                    }),
                TextColumn::make('dj_asignado_por.name')
                    ->label('DJ Asignado por')
                    ->sortable()
                    ->hidden(function () {
                        return auth()->user()->hasRole('sales_operator');
                    }),
                TextColumn::make('dj_asignado_at')
                    ->label('Fecha de Asignación')
                    ->dateTime()
                    ->sortable()
                    ->hidden(function () {
                        return auth()->user()->hasRole('sales_operator');
                    }),
            ])
            ->filters([
                SelectFilter::make('tipo_evento')
                    ->options([
                        'boda' => 'Boda',
                        'cumpleaños' => 'Cumpleaños',
                        'fiesta_corporativa' => 'Fiesta Corporativa',
                        'evento_privado' => 'Evento Privado',
                        'otro' => 'Otro'
                    ])
                    ->label('Tipo de Evento'),
                Filter::make('fecha_estimada')
                    ->form([
                        DatePicker::make('from')
                            ->label('Fecha inicial')
                            ->default(null),
                        DatePicker::make('until')
                            ->label('Fecha final')
                            ->default(null),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_estimada', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_estimada', '<=', $date),
                            );
                    }),
                SelectFilter::make('estado')
                    ->options([
                        '0' => 'Pendiente',
                        '1' => 'Aprobado'
                    ])
                    ->label('Estado')
                    ->visible(function () {
                        return !auth()->user()->hasRole('data_manager');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('contactar')
                    ->label('Contactar')
                    ->icon('heroicon-o-phone')
                    ->action(function (Clientes $record) {
                        return redirect()->away('tel:+' . $record->contacto, '_blank');
                    }),
                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->action(function (Clientes $record) {
                        return redirect()->away('https://wa.me/' . $record->contacto, '_blank');
                    }),
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateClientes::route('/create'),
            'edit' => Pages\EditClientes::route('/{record}/edit'),
            //'view' => Pages\ViewCliente::route('/{record}'),
        ];
    }
}
