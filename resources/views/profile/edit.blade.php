<x-comprador-layout>
<div class="max-w-xl mx-auto space-y-6">

    <h1 class="text-2xl font-bold text-white">Mi Perfil</h1>

    @if(session('status') === 'profile-updated')
        <div class="rounded-lg px-4 py-3 text-sm font-medium" style="background-color:#1a3d2e; color:#4ade80; border:1px solid #2d6a4f;">
            ✓ Perfil actualizado correctamente.
        </div>
    @endif
    @if(session('status') === 'avatar-deleted')
        <div class="rounded-lg px-4 py-3 text-sm font-medium" style="background-color:#1a3d2e; color:#4ade80; border:1px solid #2d6a4f;">
            ✓ Foto eliminada.
        </div>
    @endif

    {{-- FORMULARIO PRINCIPAL --}}
    <div class="rounded-xl p-6" style="background-color:#051F20; border:1px solid #2d6a4f;">

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- AVATAR --}}
            <div class="flex items-center gap-5">
                @if(auth()->user()->avatar_url)
                    <img id="avatar-preview" src="{{ auth()->user()->avatar_url }}"
                         class="w-20 h-20 rounded-full object-cover flex-shrink-0"
                         style="border:3px solid #4ade80;">
                @else
                    <div id="avatar-inicial"
                         class="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold flex-shrink-0"
                         style="background-color:#2d6a4f; color:#fff; border:3px solid #4ade80;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <img id="avatar-preview" src="" class="w-20 h-20 rounded-full object-cover flex-shrink-0 hidden"
                         style="border:3px solid #4ade80;">
                @endif

                <div class="flex flex-col gap-1">
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden">
                    <button type="button" onclick="document.getElementById('avatar-input').click()"
                            class="px-4 py-2 rounded-lg text-sm font-semibold w-fit"
                            style="background-color:#1a3d2e; color:#4ade80; border:1px solid #2d6a4f;">
                        Cambiar foto
                    </button>
                    <p id="file-name" class="text-xs hidden" style="color:#94a3b8;"></p>
                    <p class="text-xs" style="color:#4a6741;">Máx. 2MB — JPG, PNG o WEBP</p>
                    @error('avatar')
                        <p class="text-xs" style="color:#f87171;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- NOMBRE --}}
            <div>
                <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Nombre completo</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none"
                       style="background-color:#0d2d1f; color:#e2e8f0; border:1px solid #2d6a4f;">
                @error('name')
                    <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none"
                       style="background-color:#0d2d1f; color:#e2e8f0; border:1px solid #2d6a4f;">
                @error('email')
                    <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
                @enderror
            </div>

            {{-- CONTRASEÑA --}}
            <div>
                <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Nueva contraseña</label>
                <input type="password" name="new_password"
                       placeholder="Déjala vacía para no cambiarla"
                       class="w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none"
                       style="background-color:#0d2d1f; color:#e2e8f0; border:1px solid #2d6a4f;">
                <p class="text-xs mt-1" style="color:#4a6741;">Mínimo 8 caracteres</p>
                @error('new_password')
                    <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
                @enderror
            </div>

            {{-- BOTONES --}}
            <div class="flex gap-3 pt-1">
                <button type="submit"
                        class="flex-1 py-2.5 rounded-lg text-sm font-semibold"
                        style="background-color:#4ade80; color:#0d1f1a;">
                    Guardar cambios
                </button>
                @if(auth()->user()->avatar)
                    <button type="button"
                            onclick="document.getElementById('form-delete-avatar').submit()"
                            class="px-4 py-2.5 rounded-lg text-sm font-semibold"
                            style="background-color:#1a3328; color:#f87171; border:1px solid #7f1d1d;">
                        Quitar foto
                    </button>
                @endif
            </div>
        </form>

        {{-- Form oculto para eliminar avatar --}}
        @if(auth()->user()->avatar)
            <form id="form-delete-avatar" method="POST" action="{{ route('profile.avatar.destroy') }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>

    {{-- BORRAR CUENTA --}}
    <div class="rounded-xl p-6" style="background-color:#051F20; border:1px solid #7f1d1d;">
        <h2 class="text-base font-semibold mb-1" style="color:#f87171;">Borrar cuenta</h2>
        <p class="text-sm mb-4" style="color:#94a3b8;">
            Una vez que elimines tu cuenta, todos tus datos serán eliminados permanentemente.
        </p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Confirma tu contraseña</label>
                <input type="password" name="password"
                       class="w-full max-w-sm rounded-lg px-4 py-2.5 text-sm focus:outline-none"
                       style="background-color:#0d2d1f; color:#e2e8f0; border:1px solid #7f1d1d;">
                @error('password', 'userDeletion')
                    <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                    onclick="return confirm('¿Estás seguro? Esta acción no se puede deshacer.')"
                    class="px-6 py-2.5 rounded-lg text-sm font-semibold"
                    style="background-color:#7f1d1d; color:#fff;">
                Borrar cuenta
            </button>
        </form>
    </div>

</div>

<script>
document.getElementById('avatar-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        const preview = document.getElementById('avatar-preview');
        const inicial = document.getElementById('avatar-inicial');
        preview.src = ev.target.result;
        preview.classList.remove('hidden');
        if (inicial) inicial.classList.add('hidden');
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-name').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});
</script>

</x-comprador-layout>