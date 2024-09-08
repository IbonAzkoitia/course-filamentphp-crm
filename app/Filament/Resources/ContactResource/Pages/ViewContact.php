<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions\Action;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    function getTitle(): string
    {
        $record = $this->getRecord();
        return $record->first_name . ' ' . $record->last_name;
    }

    public function mount($record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->url(fn ($record) => ContactResource::getUrl('edit', ['record' => $record]))
        ];
    }
}