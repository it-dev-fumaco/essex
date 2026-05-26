<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Documents';

    protected static ?string $navigationLabel = 'Documents';

    protected static ?string $modelLabel = 'Document';

    protected static ?string $pluralModelLabel = 'Documents';

    protected static ?int $navigationSort = 1;

    protected static bool $isDiscovered = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Document details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Document name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('File')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('file')
                            ->label('File')
                            ->collection('file')
                            ->disk(config('media-library.disk_name'))
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'image/webp',
                            ])
                            ->maxSize(10240)
                            ->downloadable()
                            ->openable()
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Document name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_type')
                    ->label('File type')
                    ->getStateUsing(function (Document $record): string {
                        $media = $record->getFirstMedia('file');

                        if (! $media) {
                            return '—';
                        }

                        return strtoupper($media->extension ?: '—');
                    }),
                Tables\Columns\TextColumn::make('uploadedByAdmin.name')
                    ->label('Uploader')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Upload date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Document $record): ?string => static::documentDownloadUrl($record))
                    ->openUrlInNewTab()
                    ->visible(fn (Document $record): bool => $record->getFirstMedia('file') !== null),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'view' => Pages\ViewDocument::route('/{record}'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['uploadedByAdmin', 'media']);
    }

    public static function documentDownloadUrl(Document $record): ?string
    {
        $media = $record->getFirstMedia('file');

        if (! $media) {
            return null;
        }

        $disk = Storage::disk($media->disk);

        if (method_exists($disk, 'temporaryUrl')) {
            try {
                return $media->getTemporaryUrl(now()->addMinutes(30));
            } catch (\Throwable) {
                // Fall through to public URL when temporary URLs are unsupported.
            }
        }

        return $media->getUrl();
    }
}
