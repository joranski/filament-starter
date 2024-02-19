<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\Team;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Pages\Auth\Login;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Filament\Support\Enums\MaxWidth;
use Filament\Enums\ThemeMode;
use Filament\Tables\Columns;
use Filament\Forms\Components;
use Filament\FontProviders\SpatieGoogleFontProvider;
use Illuminate\Validation\Rules\Password;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return  
        // (
        //     request()->path() != 'settings'
        //     && request()->method() == 'GET'
        //     && isset(app('settings')->site_maintenance)
        //     && app('settings')->site_maintenance
        // ) ? $panel->id('down') :
            $panel->id('admin')
            ->default()
            //->path('')//->path('admin')
            ->domain(config('app.domain'))
            ->login()//Login::class)
            ->brandName(config('app.name'))
            ->authGuard('web')
            ->topNavigation()
            ->sidebarCollapsibleOnDesktop()
            ->font('Ubuntu', provider: SpatieGoogleFontProvider::class)
            ->colors([
                //slate,gray,zinc,neutral,stone,red,orange,amber,yellow,lime,green,emerald,teal,
                //cyan,sky,blue,indigo,violet,purple,fuchsia,pink,rose' => static::Rose,
                'primary' => '#428bca',
                'secondary' => '#DB5705',
                'danger' => '#9e0000',
                'gray' => '#8d8da2',
                'silver' => '#B9B1A6',
                'yellow' => '#E5B817',
                //'sky' => '#B2C6D6',
                'info' => '#0a3161',
                'success' => '#46b389',
                'warning' => '#bf9c77',
            ]+Color::all())
            ->defaultThemeMode(ThemeMode::Light)
            ->maxContentWidth(MaxWidth::Full)
            ->brandLogo(fn () => view('logos.light'))
            ->darkModeBrandLogo(fn () => view('logos.dark'))
            ->favicon(asset('/assets/logos/va-swm.png', config('app.https')))
            ->brandLogoHeight('2rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin::make(),
                \Filament\SpatieLaravelTranslatablePlugin::make(),
                // \ChrisReedIO\Socialment\SocialmentPlugin::make()
                //     ->registerProvider('google', 'fab-google', '')
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        hasAvatars: false, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                    )
                    //->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
                    ->avatarUploadComponent(fn() =>
                        SpatieMediaLibraryFileUpload::make('avatar_url')
                            ->collection('avatars')
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->disableLabel()
                    )
                    //FileUpload::make('myUpload')->disk('profile-photos'))
                    ->passwordUpdateRules(
                        rules: [Password::default()->mixedCase()->uncompromised(3)], // you may pass an array of validation rules as well. (default = ['min:8'])
                        requiresCurrentPassword: true, // when false, the user can update their password without entering their current password. (default = true)
                    )
                    // ->enableSanctumTokens(
                    //    permissions: ['my','custom','permissions'] // optional, customize the permissions (default = ["create", "view", "update", "delete"])
                    // )
                    ->enableTwoFactorAuthentication(
                        force: false, // force the user to enable 2FA before they can use the application (default = false)
                        // action: CustomTwoFactorPage::class // optionally, use a custom 2FA page
                    ),
            ])
            ->tenant(
                Team::class,
                slugAttribute: 'slug'
            );
    }

    public function boot()
    {
        $this->configureFormDefaults();
        $this->configureTableDefaults();
    }

    public function configureFormDefaults()
    {
        Components\TextInput::macro(name:'generateSlug', macro: function(string $name = null) {
            $this
                ->live(onBlur: true)
                ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) use ($name) {
                    if ($operation !== 'create') {
                        return;
                    }
                    $set($name??'slug', Str::slug($state));
                });
            return $this;
        });
        Components\TextInput::macro(name:'money', macro: function(float $default = null) {
            $this
                ->numeric()
                ->step(0.01)
                ->prefix(config('company.locale.currency_symbol'))
                ->rules(['regex:/^\d{1,10}(\.\d{0,2})?$/','min:0']);
            if(!is_null($default)) $this->default(round((float)$default,2));
            return $this;
        });
        Components\TextInput::macro(name:'percent', macro: function(float $default = null) {
            $this
                ->numeric()
                ->step(0.01)
                ->suffix('%')
                ->rules(['regex:/^\d{1,10}(\.\d{0,2})?$/','min:0']);
            if(!is_null($default)) $this->default(round((float)$default,2));
            return $this;
        });
        Components\TextInput::macro(name:'int', macro: function(float $default = null) {
            $this
                ->numeric()
                ->step(1)
                ->prefix(config('company.locale.currency_symbol'))
                ->rules(['regex:/^\d{1,10}$/', 'integer', 'min:0']);
            if(!is_null($default)) $this->default(round((float)$default));
            return $this;
        });


        Components\TextInput::configureUsing(function (Components\TextInput $component): void {
            $component->translateLabel()
                ->maxLength(255)
                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*(\s.*)?$/');
        });
        Components\Select::configureUsing(function (Components\Select $component): void {
            $component->translateLabel();
        });
        Components\Checkbox::configureUsing(function (Components\Checkbox $component): void {
            $component->translateLabel();
        });
        Components\Toggle::configureUsing(function (Components\Toggle $component): void {
            $component->translateLabel();
        });
        Components\CheckboxList::configureUsing(function (Components\CheckboxList $component): void {
            $component->translateLabel();
        });
        Components\Radio::configureUsing(function (Components\Radio $component): void {
            $component->translateLabel();
        });
        Components\DateTimePicker::configureUsing(function (Components\DateTimePicker $component): void {
            $component->translateLabel();
        });
        Components\DatePicker::configureUsing(function (Components\DatePicker $component): void {
            $component->translateLabel();
        });
        Components\TimePicker::configureUsing(function (Components\TimePicker $component): void {
            $component->translateLabel();
        });
        Components\FileUpload::configureUsing(function (Components\FileUpload $component): void {
            $component->translateLabel();
        });
        Components\RichEditor::configureUsing(function (Components\RichEditor $component): void {
            $component->translateLabel();
        });
        Components\MarkdownEditor::configureUsing(function (Components\MarkdownEditor $component): void {
            $component->translateLabel()
                ->fileAttachmentsDirectory('private')
                ->fileAttachmentsVisibility('private');
        });
        Components\Repeater::configureUsing(function (Components\Repeater $component): void {
            $component->translateLabel();
        });
        Components\Builder::configureUsing(function (Components\Builder $component): void {
            $component->translateLabel();
        });
        Components\TagsInput::configureUsing(function (Components\TagsInput $component): void {
            $component->translateLabel();
        });
        Components\Textarea::configureUsing(function (Components\Textarea $component): void {
            $component->translateLabel()
                ->autosize();
        });
        Components\KeyValue::configureUsing(function (Components\KeyValue $component): void {
            $component->translateLabel();
        });
        Components\ColorPicker::configureUsing(function (Components\ColorPicker $component): void {
            $component->translateLabel();
        });
        Components\Hidden::configureUsing(function (Components\Hidden $component): void {
            $component->translateLabel();
        });
    }

    public function configureTableDefaults(){
        Columns\TextColumn::macro(name:'money', macro: function() {
            $this->numeric()
                ->step(0.01)
                ->alignEnd()
                ->prefix(config('company.locale.currency_symbol'));
            return $this;
        });
        Columns\TextColumn::macro(name:'percent', macro: function() {
            $this->numeric()
                ->suffix('%');
            return $this;
        });
        Columns\TextColumn::macro(name:'tel', macro: function() {
            $this->icon('heroicon-m-phone');
            return $this;
        });
        Columns\SpatieMediaLibraryImageColumn::macro(name:'images', macro: function() {
            $this->circular()
                ->stacked()
                ->ring(5)
                ->limit(2)
                ->limitedRemainingText()
                ->defaultImageUrl(url('/assets/images/placeholder.png'))
                ->extraImgAttributes(['loading' => 'lazy']);
            return $this;
        });

        Columns\TextColumn::configureUsing(function (Columns\TextColumn $column): void {
            $column
                ->translateLabel()
                ->wrapHeader()
                ->alignEnd()
                ->sortable()
                ->toggleable()
                ->placeholder('-')
                ->limit(20)
                ->tooltip(function (Columns\TextColumn $column): ?string {
                    $state = $column->getState();
                    $limit = $column->getCharacterLimit();
                    switch(gettype($state)) {
                        case 'string':
                            return (strlen($state) <= $limit) ? null : $state;
                        case 'array':
                            $ret = [];
                            $overLimit = array_filter($state, function($v) use ($limit, &$ret) {
                                $ret[] = $v;
                                return strlen($v) > $limit;
                            });
                            return empty($overLimit) ? null : implode(", ", $ret);
                    }
                    return null;
                });
        });
        Columns\IconColumn::configureUsing(function (Columns\IconColumn $column): void {
            $column->translateLabel();
        });
        Columns\ImageColumn::configureUsing(function (Columns\ImageColumn $column): void {
            $column->translateLabel();
        });
        Columns\ColorColumn::configureUsing(function (Columns\ColorColumn $column): void {
            $column->translateLabel();
        });
        Columns\ToggleColumn::configureUsing(function (Columns\ToggleColumn $column): void {
            $column->translateLabel();
        });
        Columns\TextInputColumn::configureUsing(function (Columns\TextInputColumn $column): void {
            $column->translateLabel();
        });
        Columns\CheckboxColumn::configureUsing(function (Columns\CheckboxColumn $column): void {
            $column->translateLabel();
        });
    }

}
