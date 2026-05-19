<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class MiPerfil extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;
    protected static ?string $navigationLabel               = 'Mi Perfil';
    protected static ?string $title                         = 'Mi Perfil';
    protected static ?string $slug                          = 'mi-perfil';
    protected static string|\UnitEnum|null $navigationGroup = 'Sistema';
    protected static ?int $navigationSort                   = 99;

    protected string $view = 'filament.pages.mi-perfil';

    public bool $editando = false;
    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'name'         => $user->name,
            'email'        => $user->email,
            'new_password' => '',
            'avatar'       => $user->avatar ?? null,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->columns(1)
            ->components([
                FileUpload::make('avatar')
                    ->label('Foto de perfil')
                    ->image()
                    ->disk('public')
                    ->directory('avatars')
                    ->imageEditor()
                    ->circleCropper()
                    ->maxSize(2048)
                    ->helperText('Maximo 2MB - JPG, PNG o WEBP')
                    ->nullable(),
                TextInput::make('name')
                    ->label('Nombre completo')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Correo electronico')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(table: 'users', column: 'email', ignorable: Auth::user()),
                TextInput::make('new_password')
                    ->label('Nueva contrasena')
                    ->password()
                    ->revealable()
                    ->nullable()
                    ->rule(Password::min(8))
                    ->placeholder('Dejalo vacio para no cambiarla')
                    ->helperText('Minimo 8 caracteres'),
            ])
            ->statePath('data');
    }

    public function toggleEditar(): void
    {
        $this->editando = !$this->editando;
    }

    public function guardar(): void
    {
        $datos = $this->form->getState();
        $user  = Auth::user();

        $avatarPath  = $user->avatar;
        $nuevoAvatar = $datos['avatar'] ?? null;

        if (!empty($nuevoAvatar)) {
            $nuevaRuta = is_array($nuevoAvatar) ? array_values($nuevoAvatar)[0] : $nuevoAvatar;
            if ($user->avatar && $nuevaRuta !== $user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $nuevaRuta;
        } elseif (is_null($nuevoAvatar) && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $avatarPath = null;
        }

        $updateData = [
            'name'   => $datos['name'],
            'email'  => $datos['email'],
            'avatar' => $avatarPath,
        ];

        if (!empty($datos['new_password'])) {
            $updateData['password'] = Hash::make($datos['new_password']);
        }

        $user->update($updateData);
        $this->editando = false;

        Auth::setUser($user->fresh());

        Notification::make()
            ->title('Perfil actualizado correctamente.')
            ->success()
            ->send();
    }

    public function cancelar(): void
    {
        $this->editando = false;
        $user = Auth::user()->fresh();
        $this->form->fill([
            'name'         => $user->name,
            'email'        => $user->email,
            'new_password' => '',
            'avatar'       => $user->avatar ?? null,
        ]);
    }
}