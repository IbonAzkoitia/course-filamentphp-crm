<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                            Forms\Components\DatePicker::make('birthday'),
                            Forms\Components\Textarea::make('description'),
                            Forms\Components\TextInput::make('job_title'),
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
                // Tables\Columns\TextColumn::make('account_name')
                //     ->label('Account')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('job_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('birthday')
                    ->date()
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
            ->recordUrl(function (Contact $record) {
                return Pages\ViewContact::getUrl(['record' => $record]);
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
                                            ->hidden(fn (Contact $record) => empty($record->image)),
                                        TextEntry::make('source.name')
                                            ->label('Contact Source')
                                            ->hidden(fn(Contact $record) => empty($record->source)),
                                        TextEntry::make('email')
                                            ->hidden(fn(Contact $record) => empty($record->email)),
                                        TextEntry::make('phone')
                                            ->hidden(fn(Contact $record) => empty($record->phone)),
                                        TextEntry::make('country')
                                            ->hidden(fn(Contact $record) => empty($record->country)),
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
                                            ->hidden(fn(Contact $record) => empty($record->url_instagram)),
                                        TextEntry::make('url_linkedin')
                                            ->label('LinkedIn')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Contact $record) => empty($record->url_linkedin)),
                                        TextEntry::make('url_tiktok')
                                            ->label('TikTok')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Contact $record) => empty($record->url_tiktok)),
                                        TextEntry::make('url_website')
                                            ->label('Website')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Contact $record) => empty($record->url_website)),
                                        TextEntry::make('url_youtube')
                                            ->label('YouTube')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('Copied')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Contact $record) => empty($record->url_youtube)),
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
                                            ->hidden(fn(Contact $record) => empty($record->account_name)),
                                        TextEntry::make('account_size.name')
                                            ->label('Size')
                                            ->hidden(fn(Contact $record) => empty($record->account_size->name)),
                                        TextEntry::make('industry.name')
                                            ->label('Industry')
                                            ->hidden(fn(Contact $record) => empty($record->industry->name)),
                                        TextEntry::make('account_revenue')
                                            ->label('Revenue')
                                            ->money('EUR')
                                            ->hidden(fn(Contact $record) => empty($record->account_revenue)),
                                        TextEntry::make('tools.name')
                                            ->label('Tools')
                                            ->formatStateUsing(function ($record) {
                                                return view('account.accountToolList', ['tools' => $record->tools])->render();
                                            })
                                            ->html()
                                            ->hidden(fn(Contact $record) => empty($record->tools))
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(),
                                Section::make('Location')
                                    ->schema([
                                        TextEntry::make('street')
                                            ->hidden(fn(Contact $record) => empty($record->street)),
                                        TextEntry::make('city')
                                            ->hidden(fn(Contact $record) => empty($record->city)),
                                        TextEntry::make('state')
                                            ->hidden(fn(Contact $record) => empty($record->state)),
                                        TextEntry::make('country')
                                            ->hidden(fn(Contact $record) => empty($record->country)),
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
            'view' => Pages\ViewContact::route('/{record}'),
        ];
    }
}
