<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\SiteSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Site settings';

    protected string $view = 'filament.pages.site-settings-page';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSettings::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Feature visibility')
                    ->description('Control which public site sections are available and visible in navigation.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('show_blog')
                                    ->label('Show blog')
                                    ->helperText('Hide blog links and return 404 for blog pages when disabled.')
                                    ->default(true)
                                    ->inline(false)
                                    ->required(),
                                Toggle::make('show_impact')
                                    ->label('Show impact')
                                    ->helperText('Hide impact links and return 404 for the impact page when disabled.')
                                    ->default(true)
                                    ->inline(false)
                                    ->required(),
                                Toggle::make('show_gallery')
                                    ->label('Show gallery')
                                    ->helperText('Hide gallery links and return 404 for the gallery page when disabled.')
                                    ->default(true)
                                    ->inline(false)
                                    ->required(),
                                Toggle::make('show_events')
                                    ->label('Show events')
                                    ->helperText('Hide event links and return 404 for the events page when disabled.')
                                    ->default(true)
                                    ->inline(false)
                                    ->required(),
                                Toggle::make('show_donations')
                                    ->label('Show donations')
                                    ->helperText('Hide donation links and return 404 for donation pages when disabled.')
                                    ->default(true)
                                    ->inline(false)
                                    ->required(),
                            ]),
                    ]),
                Section::make('Future-ready contact details')
                    ->description('Optional global values that can be reused across the site as more settings are added.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('contact_email')
                                    ->label('Contact email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('donations_email')
                                    ->label('Donations email')
                                    ->email()
                                    ->maxLength(255),
                            ]),
                    ]),
                Section::make('Contact page social links')
                    ->description('Only links with URLs are shown on the public contact page. Leave every field blank to hide the Social heading entirely.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('facebook_url')
                                    ->label('Facebook URL')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('instagram_url')
                                    ->label('Instagram URL')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('youtube_url')
                                    ->label('YouTube URL')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('tiktok_url')
                                    ->label('TikTok URL')
                                    ->url()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $settings = SiteSettings::query()->firstOrCreate([], SiteSettings::defaultAttributes());
        $settings->fill($this->form->getState());
        $settings->save();

        Notification::make()
            ->title('Site settings saved')
            ->success()
            ->send();
    }
}
