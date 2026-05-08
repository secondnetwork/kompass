import { Passkeys } from '@laravel/passkeys';

function passkeyErrorMessage(e) {
    if (!window.isSecureContext) {
        return 'Passkeys require HTTPS. Please access this site via https://.';
    }
    return e.message ?? 'Passkey operation failed.';
}

export function passkey_authenticate() {
    return {
        loading: false,
        error: null,

        async authenticate() {
            this.loading = true;
            this.error = null;

            try {
                const response = await Passkeys.verify();
                window.location.href = response?.redirect ?? '/admin/dashboard';
            } catch (e) {
                this.loading = false;
                this.error = passkeyErrorMessage(e);
            }
        },
    };
}

export function passkey_register() {
    return {
        loading: false,
        error: null,
        success: false,
        name: '',

        async register() {
            if (!this.name.trim()) {
                this.error = 'Please enter a name for this passkey.';
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                await Passkeys.register({ name: this.name.trim() });
                this.success = true;
                this.loading = false;
                window.dispatchEvent(new CustomEvent('passkey-registered', { detail: { name: this.name } }));
                this.name = '';
            } catch (e) {
                this.loading = false;
                this.error = passkeyErrorMessage(e);
            }
        },
    };
}
