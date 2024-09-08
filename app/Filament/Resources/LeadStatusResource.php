<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadStatusResource\Pages;
use App\Filament\Resources\LeadStatusResource\RelationManagers;
use App\Models\LeadStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadStatusResource extends Resource
{
    protected static ?string $model = LeadStatus::class;

    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return LeadStatus::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return LeadStatus::count() > 1 ? 'gray' : 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leads_count')
                    ->counts('leads')
                    ->label('Leads')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListLeadStatuses::route('/'),
            'create' => Pages\CreateLeadStatus::route('/create'),
            'edit' => Pages\EditLeadStatus::route('/{record}/edit'),
        ];
    }
}
