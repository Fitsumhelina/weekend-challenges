<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactinfoResource\Pages;
use App\Filament\Resources\ContactinfoResource\RelationManagers;
use App\Models\Contactinfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Policies\GenericPolicy;
use Illuminate\Support\Facades\Auth;

class ContactinfoResource extends Resource
{
    protected static ?string $model = Contactinfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('detail')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->label('Icon (CSS class)')
                    ->maxLength(255),
            ])->columns(2);
    }

    /**
     * Authorization using GenericPolicy
     */

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user ? (new GenericPolicy)->create($user, Contactinfo::class) : false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = Auth::user();
        return $user ? (new GenericPolicy)->update($user, $record) : false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = Auth::user();
        return $user ? (new GenericPolicy)->delete($user, $record) : false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('detail')->sortable(),
                Tables\Columns\TextColumn::make('icon')->label('Icon'),
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
            'index' => Pages\ListContactinfos::route('/'),
            'create' => Pages\CreateContactinfo::route('/create'),
            'edit' => Pages\EditContactinfo::route('/{record}/edit'),
        ];
    }
}
