<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
                ->label('Download')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (Document $record): ?string => DocumentResource::documentDownloadUrl($record))
                ->openUrlInNewTab()
                ->visible(fn (Document $record): bool => $record->getFirstMedia('file') !== null),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Document details')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label('Document name'),
                        Infolists\Components\TextEntry::make('description')
                            ->placeholder('—')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('file_type')
                            ->label('File type')
                            ->getStateUsing(function (Document $record): string {
                                $media = $record->getFirstMedia('file');

                                if (! $media) {
                                    return '—';
                                }

                                return strtoupper($media->extension ?: $media->mime_type);
                            }),
                        Infolists\Components\TextEntry::make('uploadedByAdmin.name')
                            ->label('Uploader'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Upload date')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}
