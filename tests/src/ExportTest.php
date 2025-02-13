<?php

use Cmdobueno\FilamentExport\Actions\FilamentExportBulkAction;
use Cmdobueno\FilamentExport\Actions\FilamentExportHeaderAction;
use Cmdobueno\FilamentExport\FilamentExport;
use Cmdobueno\FilamentExport\Tests\Filament\Resources\PostResource;
use Cmdobueno\FilamentExport\Tests\Filament\Resources\PostResource\Pages\ListPosts;
use Cmdobueno\FilamentExport\Tests\Filament\Resources\UserResource;
use Cmdobueno\FilamentExport\Tests\Filament\Resources\UserResource\Pages\ListUsers;
use Cmdobueno\FilamentExport\Tests\Filament\Resources\UserResource\RelationManagers\PostsRelationManager;
use Cmdobueno\FilamentExport\Tests\Models\Post;
use Cmdobueno\FilamentExport\Tests\Models\User;
use Filament\Tables\Table;
use function Pest\Livewire\livewire;
use Symfony\Component\HttpFoundation\StreamedResponse;

it('can initiate tests', function () {
    expect(true)->toBe(true);
});

it('can create user', function () {
    expect(User::factory()->create())->toBeInstanceOf(User::class);
});

it('can create post', function () {
    expect(Post::factory()->create())->toBeInstanceOf(Post::class);
});

it('can retrieve user', function () {
    User::factory()->create();
    expect(User::latest()->first())->toBeInstanceOf(User::class);
});

it('can retrieve post', function () {
    Post::factory()->create();
    expect(Post::latest()->first())->toBeInstanceOf(Post::class);
});

it('can render post resource page', function () {
    $this->get(PostResource::getUrl('index'))->assertSuccessful();
});

it('can list posts', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords($posts);
});

it('can call header action', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(ListPosts::class)
        ->callTableAction('export')
        ->assertSuccessful();
});

it('can call bulk action', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(ListPosts::class)
        ->callTableBulkAction('export', $posts)
        ->assertSuccessful();
});

foreach (FilamentExport::DEFAULT_FORMATS as $format => $label) {
    it("can header action call $format download", function () use ($format) {
        $posts = Post::factory()->count(10)->create();

        $livewire = new ListPosts();

        $livewire->bootedInteractsWithTable();

        $action = FilamentExportHeaderAction::make('export')
            ->table(Table::make($livewire));

        expect(
            FilamentExport::callDownload($action, $posts, [
                'file_name' => null,
                'filter_columns' => [],
                'additional_columns' => [],
                'format' => "$format",
                'page_orientation' => null,
            ])
        )->toBeInstanceOf(StreamedResponse::class);
    });
}

foreach (FilamentExport::DEFAULT_FORMATS as $format => $label) {
    it("can bulk action call $format download", function () use ($format) {
        $posts = Post::factory()->count(10)->create();

        $livewire = new ListPosts();

        $livewire->bootedInteractsWithTable();

        $action = FilamentExportBulkAction::make('export')
            ->table(Table::make($livewire));

        expect(
            FilamentExport::callDownload($action, $posts, [
                'file_name' => null,
                'filter_columns' => [],
                'additional_columns' => [],
                'format' => "$format",
                'page_orientation' => null,
            ])
        )->toBeInstanceOf(StreamedResponse::class);
    });
}

it('can render user resource page', function () {
    $this->get(UserResource::getUrl('index'))->assertSuccessful();
});

it('can list users', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($posts);
});

it('can view user with posts relation manager', function () {
    $posts = Post::factory()->count(10)->create();

    $this->get(PostResource::getUrl('view', [
        'record' => $posts->first()->id,
    ]))->assertSuccessful();
});

it('can render relation manager', function () {
    Post::factory()->count(10)->create();

    livewire(PostsRelationManager::class, [
        'ownerRecord' => User::latest()->first(),
    ])->assertSuccessful();
});

it('can call header action on relation manager', function () {
    Post::factory()->create();

    $user = User::latest()->first();

    livewire(PostsRelationManager::class, [
        'ownerRecord' => $user,
    ])
        ->callTableAction('export')
        ->assertSuccessful();
});

it('can call bulk action on relation manager', function () {
    Post::factory()->create();

    $user = User::latest()->first();

    livewire(PostsRelationManager::class, [
        'ownerRecord' => $user,
    ])
        ->callTableBulkAction('export', $user->posts)
        ->assertSuccessful();
});
