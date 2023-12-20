<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup='Blog';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {   
   
        return $form
            ->schema([
                Section::make('Create new Post')->schema([
                    TextInput::make('title')->label('Title')->required(),
                    MarkdownEditor::make('content')->label('Content')->required(),
                    
                    Hidden::make('user_id')
                    ->dehydrateStateUsing(fn ($state)=> auth()->user()->id)
                ]),
            ]);

    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                ->searchable(),
                Tables\Columns\TextColumn::make('content')
                ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('Published At')
                ->sortable()
                ->date(),
                Tables\Columns\TextColumn::make('user.name')->label('Author')
                ->toggleable(),

            ])
            ->filters([
               
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateActions([
            
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }    
}
