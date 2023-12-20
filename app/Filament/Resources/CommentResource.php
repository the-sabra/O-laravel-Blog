<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter ;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $navigationGroup='Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('content')->required(),
                TextInput::make('post_id')->numeric()->required(),
                Select::make('parent_id')
                ->label('parent_comment')
                ->options(Comment::all()->pluck('content','id'))
                ->searchable(),
                Hidden::make('user_id')
                ->dehydrateStateUsing(fn ($state)=> auth()->user()->id)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                ->searchable(),
                TextColumn::make('content'),
                TextColumn::make('created_at')
                ->date()
                ->sortable(),
                TextColumn::make('post.title')->label('Post Title')
        
            ])
            ->filters([
                Filter::make('is_Child')->toggle()
                ->query(fn (Builder $query)=> $query->whereNotNull('parent_id')),
                Filter::make('is_Parent')->toggle()
                ->query(fn (Builder $query)=> $query->where('parent_id',null)),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }    
}
