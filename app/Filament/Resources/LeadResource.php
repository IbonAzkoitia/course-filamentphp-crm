<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getNavigationBadge(): ?string
    {
        return Lead::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Info')
                        ->columns(2)
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Photo')
                                ->directory('contacts')
                                ->preserveFilenames()
                                ->avatar()
                                ->imageEditor()
                                ->circleCropper()
                                ->maxSize(1024)
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('first_name'),
                            Forms\Components\TextInput::make('last_name'),
                            Forms\Components\TextInput::make('email')
                                ->email(),
                            Forms\Components\TextInput::make('phone'),
                            Forms\Components\Textarea::make('description'),
                            Forms\Components\TextInput::make('job_title'),
                            Forms\Components\Select::make('lead_status_id')
                                ->default(1)
                                ->relationship(
                                    name: 'leadStatus',
                                    modifyQueryUsing: fn (Builder $query) => $query->orderBy('id'),
                                )
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}"),
                            Forms\Components\Select::make('source_id')
                                ->relationship('source', 'name')
                        ]),
                    Wizard\Step::make('Social Media')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('url_linkedin')
                                ->label('LinkedIn')
                                ->url()
                                ->placeholder('https://linkedin.com/'),
                            Forms\Components\TextInput::make('url_website')
                                ->label('Website')
                                ->url()
                                ->placeholder('https://prolinks.pro/'),
                            Forms\Components\TextInput::make('url_x')
                                ->label('X')
                                ->url()
                                ->placeholder('https://x.com/'),
                        ]),
                    Wizard\Step::make('Address')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('street'),
                            Forms\Components\TextInput::make('city'),
                            Forms\Components\TextInput::make('state'),
                            Forms\Components\TextInput::make('postcode'),
                            Forms\Components\TextInput::make('country'),
                        ]),
                    Wizard\Step::make('Company')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('account_name'),
                            Forms\Components\TextInput::make('account_revenue')
                                ->numeric(),
                            Forms\Components\Select::make('account_size_id')
                                ->relationship(
                                    name: 'accountSize',
                                    modifyQueryUsing: fn (Builder $query) => $query->orderBy('id'),
                                )
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}"),
                            Forms\Components\Select::make('industry_id')
                                ->relationship('industry', 'name')
                        ]),
                ])
                ->columnSpan(3),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Full Name')
                    ->formatStateUsing(function ($record) {
                        return $record->first_name . ' ' . $record->last_name;
                    })
                    ->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('account_name')
                    ->label('Account')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accountSize.name')
                    ->label('Size'),
                Tables\Columns\TextColumn::make('job_title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('leadstatus.name')
                    ->formatStateUsing(function ($record) {
                        return view('lead.leadStatusList', ['leadStatus' => $record->leadStatus])->render();
                    })
                    ->html()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('source.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('url_linkedin')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('url_website')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('url_x')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('street')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('postcode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('account_revenue')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('industry.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('partner.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            ->recordUrl(function (Lead $record) {
                return Pages\ViewLead::getUrl(['record' => $record]);
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 12
                    ])
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make()
                                    ->schema([
                                        ImageEntry::make('image')
                                            ->label('')
                                            ->height(60)
                                            ->circular()
                                            ->hidden(fn (Lead $record) => empty($record->image)),
                                        TextEntry::make('leadStatus.name')
                                            ->label('Status')
                                            ->formatStateUsing(function ($record) {
                                                return view('lead.leadStatusList', ['leadStatus' => $record->leadStatus])->render();
                                            })
                                            ->html(),
                                        TextEntry::make('partner.name')
                                            ->label('Referred to')
                                            ->visible(fn(Lead $record) => $record->leadStatus->name === 'Referrer'),
                                        TextEntry::make('source.name')
                                            ->label('Lead Source')
                                            ->hidden(fn(Lead $record) => empty($record->source)),
                                        TextEntry::make('email')
                                            ->hidden(fn(Lead $record) => empty($record->email)),
                                        TextEntry::make('phone')
                                            ->hidden(fn(Lead $record) => empty($record->phone)),
                                        TextEntry::make('country')
                                            ->hidden(fn(Lead $record) => empty($record->country)),
                                        TextEntry::make('description')
                                            ->columnSpanFull(),
                                            
                                    ])
                                    ->columns(3),
                                Section::make('Social Media')
                                    ->schema([
                                        TextEntry::make('url_instagram')
                                            ->label('Instagram')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_instagram)),
                                        TextEntry::make('url_linkedin')
                                            ->label('LinkedIn')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_linkedin)),
                                        TextEntry::make('url_tiktok')
                                            ->label('TikTok')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_tiktok)),
                                        TextEntry::make('url_website')
                                            ->label('Website')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_website)),
                                        TextEntry::make('url_youtube')
                                            ->label('YouTube')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_youtube)),
                                    ])
                                    ->columns(),
                            ])
                            ->columnSpan(7),
                        Grid::make()
                            ->schema([
                                Section::make('Account')
                                    ->schema([
                                        TextEntry::make('account_name')
                                            ->label('Account')
                                            ->hidden(fn(Lead $record) => empty($record->account_name)),
                                        TextEntry::make('account_size.name')
                                            ->label('Size')
                                            ->hidden(fn(Lead $record) => empty($record->account_size->name)),
                                        TextEntry::make('industry.name')
                                            ->label('Industry')
                                            ->hidden(fn(Lead $record) => empty($record->industry->name)),
                                        TextEntry::make('account_revenue')
                                            ->label('Revenue')
                                            ->money('EUR')
                                            ->hidden(fn(Lead $record) => empty($record->account_revenue)),
                                        TextEntry::make('tools.name')
                                            ->label('Tools')
                                            ->formatStateUsing(function ($record) {
                                                return view('account.accountToolList', ['tools' => $record->tools])->render();
                                            })
                                            ->html()
                                            ->hidden(fn(Lead $record) => empty($record->tools))
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(),
                                Section::make('Location')
                                    ->schema([
                                        TextEntry::make('street')
                                            ->hidden(fn(Lead $record) => empty($record->street)),
                                        TextEntry::make('city')
                                            ->hidden(fn(Lead $record) => empty($record->city)),
                                        TextEntry::make('state')
                                            ->hidden(fn(Lead $record) => empty($record->state)),
                                        TextEntry::make('country')
                                            ->hidden(fn(Lead $record) => empty($record->country)),
                                    ])
                                    ->columns()
                            ])
                            ->columnSpan(5),
                    ])
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
            'view' => Pages\ViewLead::route('/{record}'),
        ];
    }
}
