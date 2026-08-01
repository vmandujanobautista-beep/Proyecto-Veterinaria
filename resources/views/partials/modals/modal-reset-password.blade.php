{{-- ═══════════════════════════════════════════════════════════
     MODAL 3 – RECUPERAR CONTRASEÑA
     Controlado por Alpine.js: activeModal === 'reset' o 'reset_success'
     Flujo en 2 fases:
       Fase 1: verificar email + admin_password
       Fase 2: ingresar nueva contraseña (no puede ser igual a la actual)
     Tras éxito → redirige a Iniciar Sesión automáticamente.
═══════════════════════════════════════════════════════════ --}}

@php
    $verifiedEmail = session('reset_verified_email');
@endphp

<div
    x-cloak
    x-show="activeModal === 'reset' || activeModal === 'reset_success'"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="activeModal = null"
>
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter: blur(4px);" @click="activeModal = null"></div>

    <div
        x-show="activeModal === 'reset' || activeModal === 'reset_success'"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 translate-y-6 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-6 scale-95"
        class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-y-auto"
        style="max-height: 88vh;"
        @click.stop
    >
        <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #f59e0b, #d97706, #0ea5e9);"></div>

        <div class="px-7 py-4">
            {{-- Encabezado --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background:linear-gradient(135deg,rgba(254,243,199,0.80),rgba(186,230,253,0.80)); border:1.5px solid rgba(245,158,11,0.35);">
                        <svg class="w-5 h-5" style="color:#d97706" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold" style="color:#78350f;">Restablecer Contraseña</h2>
                        <p class="text-xs" style="color:#64748b;">
                            @if (session('success_modal') === 'reset_success')
                                Completado
                            @elseif ($verifiedEmail)
                                Paso 2: Nueva contraseña
                            @else
                                Paso 1: Verificar identidad
                            @endif
                        </p>
                    </div>
                </div>
                <button type="button" @click="activeModal = null"
                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-colors"
                        style="color:#94a3b8;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- ══ ESTADO: Contraseña cambiada con éxito (transición a login) ══ --}}
            @if (session('success_modal') === 'reset_success')
                <div class="py-6 text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#ecfdf5; border:2px solid #34d399;">
                        <svg class="w-8 h-8" style="color:#059669" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color:#065f46;">¡Contraseña Actualizada!</h3>
                    <p class="text-sm mb-4" style="color:#475569;">
                        {{ session('success_message') }}
                    </p>
                    <p class="text-xs font-semibold animate-pulse" style="color:#0ea5e9;">
                        🔄 Abriendo Inicio de Sesión de forma automática...
                    </p>
                </div>

            {{-- ══ FASE 2: Formulario de nueva contraseña (si ya validamos en Paso 1) ══ --}}
            @elseif ($verifiedEmail)
                <div class="mb-3 px-4 py-2 rounded-xl text-xs font-medium" style="background:#fef3c7; border:1px solid #fde68a; color:#92400e;">
                    👤 Identidad verificada para <strong>{{ $verifiedEmail }}</strong>.<br>
                    Por seguridad, la nueva contraseña no puede ser igual a tu contraseña actual.
                </div>

                <form method="POST" action="{{ route('modal.reset-password') }}"
                      role="presentation"
                      autocomplete="off"
                      autocorrect="off"
                      autocapitalize="off"
                      spellcheck="false"
                      x-data="{
                          new_password: '',
                          new_password_confirmation: '',
                          errors: {
                              new_password: @js($errors->first('new_password') ?: ''),
                              new_password_confirmation: @js($errors->first('new_password_confirmation') ?: '')
                          },
                          init() {
                              this.$watch('activeModal', val => {
                                  if (val !== 'reset' && val !== 'reset_success') {
                                      this.errors.new_password = '';
                                      this.errors.new_password_confirmation = '';
                                  }
                              });
                          },
                          validateNewPassword() {
                              this.errors.new_password = window.VetCareValidators.password(this.new_password);
                              if (this.new_password_confirmation) {
                                  this.validateConfirmation();
                              }
                          },
                          validateConfirmation() {
                              if (!this.new_password_confirmation) {
                                  this.errors.new_password_confirmation = 'Debes confirmar la nueva contraseña.';
                              } else if (this.new_password !== this.new_password_confirmation) {
                                  this.errors.new_password_confirmation = 'Las contraseñas no coinciden.';
                              } else {
                                  this.errors.new_password_confirmation = '';
                              }
                          },
                          validateAll(e) {
                              this.validateNewPassword();
                              this.validateConfirmation();
                              if (this.errors.new_password || this.errors.new_password_confirmation) {
                                  e.preventDefault();
                              }
                          }
                      }"
                      @submit="validateAll($event)">
                    @csrf
                    <input type="hidden" name="email" value="{{ $verifiedEmail }}">
                    <input type="hidden" name="admin_password" value="PASSWORD">

                    {{-- Honeypot --}}
                    <input type="password" style="display:none" name="_fake_pw1" autocomplete="new-password">

                    {{-- Nueva Contraseña con Ojo --}}
                    <div class="mb-3" x-data="{ showP: false }">
                        <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">Nueva Contraseña</label>
                        <div class="relative">
                            <input :type="showP ? 'text' : 'password'" name="new_password" id="reset_new_password"
                                   x-model="new_password"
                                   @input="validateNewPassword()"
                                   @blur="validateNewPassword()"
                                   @keydown.stop
                                   required
                                   autocomplete="new-password"
                                   autocorrect="off"
                                   autocapitalize="off"
                                   spellcheck="false"
                                   placeholder="Mínimo 8 caracteres"
                                   class="w-full px-4 py-3 pr-12 rounded-xl text-sm transition-all outline-none"
                                   style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc; user-select:text; -webkit-user-select:text;"
                                   onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.12)';"
                                   onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            <button type="button" @click="showP = !showP" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center" style="color:#94a3b8;">
                                <svg x-show="!showP" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg x-show="showP" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <p x-cloak x-show="errors.new_password" x-text="errors.new_password" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                    </div>

                    {{-- Confirmar Nueva Contraseña con Ojo --}}
                    <div class="mb-4" x-data="{ showP2: false }">
                        <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">Confirmar Nueva Contraseña</label>
                        <div class="relative">
                            <input :type="showP2 ? 'text' : 'password'" name="new_password_confirmation" id="reset_new_password_confirmation"
                                   x-model="new_password_confirmation"
                                   @input="validateConfirmation()"
                                   @blur="validateConfirmation()"
                                   @keydown.stop
                                   required
                                   autocomplete="new-password"
                                   autocorrect="off"
                                   autocapitalize="off"
                                   spellcheck="false"
                                   placeholder="Repite tu nueva contraseña"
                                   class="w-full px-4 py-3 pr-12 rounded-xl text-sm transition-all outline-none"
                                   style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc; user-select:text; -webkit-user-select:text;"
                                   onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.12)';"
                                   onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            <button type="button" @click="showP2 = !showP2" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center" style="color:#94a3b8;">
                                <svg x-show="!showP2" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg x-show="showP2" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <p x-cloak x-show="errors.new_password_confirmation" x-text="errors.new_password_confirmation" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-xl font-semibold text-white text-sm transition-all"
                            style="background:linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 4px 18px rgba(245,158,11,0.28);"
                            onmouseover="this.style.background='linear-gradient(135deg,#fbbf24,#f59e0b)'; this.style.boxShadow='0 6px 22px rgba(245,158,11,0.40)';"
                            onmouseout="this.style.background='linear-gradient(135deg,#f59e0b,#d97706)'; this.style.boxShadow='0 4px 18px rgba(245,158,11,0.28)';">
                        Guardar Nueva Contraseña
                    </button>
                </form>

            {{-- ══ FASE 1: Formulario para validar email y contraseña admin ══ --}}
            @else
                <p class="text-sm mb-5" style="color:#475569;">
                    Ingresa tu correo y la contraseña de administrador para verificar tu identidad antes de cambiar la contraseña.
                </p>

                <form method="POST" action="{{ route('modal.reset-password') }}"
                      role="presentation"
                      autocomplete="off"
                      autocorrect="off"
                      autocapitalize="off"
                      spellcheck="false"
                      x-data="{
                          email: @js(old('email', '')),
                          admin_password: '',
                          errors: {
                              email: @js($errors->first('email') ?: ''),
                              admin_password: @js($errors->first('admin_password') ?: '')
                          },
                          init() {
                              this.$watch('activeModal', val => {
                                  if (val !== 'reset') {
                                      this.errors.email = '';
                                      this.errors.admin_password = '';
                                  }
                              });
                          },
                          validateEmail() {
                              this.errors.email = window.VetCareValidators.email(this.email);
                          },
                          validateAdminPassword() {
                              if (!this.admin_password || this.admin_password.trim() === '') {
                                  this.errors.admin_password = 'La contraseña de administrador es obligatoria.';
                              } else {
                                  this.errors.admin_password = '';
                              }
                          },
                          validateAll(e) {
                              this.validateEmail();
                              this.validateAdminPassword();
                              if (this.errors.email || this.errors.admin_password) {
                                  e.preventDefault();
                              }
                          }
                      }"
                      @submit="validateAll($event)">
                    @csrf

                    {{-- Honeypot --}}
                    <input type="text"     style="display:none" name="_fake_email2" autocomplete="email">
                    <input type="password" style="display:none" name="_fake_pw2"   autocomplete="new-password">

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1.5" style="color:#374151;" for="reset_email">Correo Electrónico del Usuario</label>
                        <input type="text" name="email" id="reset_email"
                               x-model="email"
                               @input="validateEmail()"
                               @blur="validateEmail()"
                               @keydown.stop
                               required
                               autocomplete="off"
                               autocorrect="off"
                               autocapitalize="off"
                               spellcheck="false"
                               placeholder="usuario@vetcare.com"
                               class="w-full px-4 py-3 rounded-xl text-sm transition-all outline-none"
                               style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc; user-select:text; -webkit-user-select:text;"
                               onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.12)';"
                               onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        <p x-cloak x-show="errors.email" x-text="errors.email" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                    </div>

                    {{-- Contraseña de Administrador --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold mb-1.5" style="color:#374151;" for="reset_admin_password">Contraseña de Administrador</label>
                        <input type="password" name="admin_password" id="reset_admin_password"
                               x-model="admin_password"
                               @input="validateAdminPassword()"
                               @blur="validateAdminPassword()"
                               @keydown.stop
                               required
                               autocomplete="new-password"
                               autocorrect="off"
                               autocapitalize="off"
                               spellcheck="false"
                               placeholder="Requerida para autorizar el cambio"
                               class="w-full px-4 py-3 rounded-xl text-sm transition-all outline-none"
                               style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc; user-select:text; -webkit-user-select:text;"
                               onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.12)';"
                               onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        <p class="mt-1.5 text-xs" style="color:#94a3b8;">⚠️ Solo un administrador puede autorizar el reseteo.</p>
                        <p x-cloak x-show="errors.admin_password" x-text="errors.admin_password" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 rounded-xl font-semibold text-white text-sm transition-all"
                            style="background:linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 4px 18px rgba(245,158,11,0.28);"
                            onmouseover="this.style.background='linear-gradient(135deg,#fbbf24,#f59e0b)'; this.style.boxShadow='0 6px 22px rgba(245,158,11,0.40)';"
                            onmouseout="this.style.background='linear-gradient(135deg,#f59e0b,#d97706)'; this.style.boxShadow='0 4px 18px rgba(245,158,11,0.28)';">
                        Verificar Identidad
                    </button>
                </form>
            @endif

            <div class="mt-4 text-center">
                <button type="button" @click="activeModal = 'login'"
                        class="text-sm hover:underline" style="color:#64748b; background:none; border:none; cursor:pointer;">
                    ← Volver a Iniciar Sesión
                </button>
            </div>
        </div>
    </div>
</div>
