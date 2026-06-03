<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use OneLogin\Saml2\IdPMetadataParser;
use Secondnetwork\Kompass\Features;
use Secondnetwork\Kompass\Models\Setting;
use Slides\Saml2\Models\Tenant;

/**
 * SAML2 tenant management. Only functional when scaler-tech/laravel-saml2 is
 * installed — every reference to the package is guarded by Features::hasSaml2()
 * and uses the Slides\Saml2\Models\Tenant model resolved at runtime, so the
 * component degrades gracefully when the package is absent.
 */
class Saml2 extends Component
{
    use WithFileUploads;

    /** @var array<int, array<string, mixed>> */
    public array $tenants = [];

    public bool $showForm = false;

    public ?int $editingId = null;

    #[Validate('required|string|max:255')]
    public string $key = '';

    #[Validate('required|string|max:255')]
    public string $idp_entity_id = '';

    #[Validate('required|url|max:2048')]
    public string $idp_login_url = '';

    #[Validate('nullable|url|max:2048')]
    public string $idp_logout_url = '';

    #[Validate('required|string')]
    public string $idp_x509_cert = '';

    #[Validate('nullable|url|max:2048')]
    public string $relay_state_url = '';

    #[Validate('nullable|string|max:255')]
    public string $name_id_format = 'persistent';

    /** @var array<int, array{value:string,label:string}> */
    public array $nameIdFormats = [
        ['value' => 'persistent', 'label' => 'Persistent'],
        ['value' => 'transient', 'label' => 'Transient'],
        ['value' => 'emailAddress', 'label' => 'Email Address'],
        ['value' => 'unspecified', 'label' => 'Unspecified'],
    ];

    // IdP metadata import.
    public string $metadataUrl = '';

    public string $metadataXml = '';

    /** @var TemporaryUploadedFile|null */
    public $metadataFile;

    /**
     * Login-page button configuration (stored as global settings, consumed by
     * resources/views/livewire/admin/auth/login.blade.php).
     */
    public bool $ssoEnabled = false;

    public string $ssoTenantUuid = '';

    public string $ssoLabel = '';

    public string $ssoIcon = 'tabler-shield-lock';

    // Shared icon picker (same pattern as the link block / PagesData,
    // rendered via <x-kompass::icon-picker />).
    public bool $FormIconPicker = false;

    public string $iconSearch = '';

    /** @var array<int, array{id:string,name:string,full_name:string}> */
    public array $filteredIcons = [];

    public function mount(): void
    {
        $this->authorizeAdmin();
        $this->loadTenants();
        $this->loadLoginButton();
    }

    /**
     * SAML2 configuration is restricted to administrators. Guards the component
     * against direct Livewire endpoint access regardless of UI visibility.
     */
    protected function authorizeAdmin(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->hasRole(['super_admin', 'admin']),
            403,
        );
    }

    public function loadLoginButton(): void
    {
        $this->ssoEnabled = (bool) setting('global.sso');
        $this->ssoTenantUuid = (string) setting('global.sso-url', '');
        $this->ssoLabel = (string) setting('global.sso-label', __('Single Sign-On'));
        $this->ssoIcon = (string) setting('global.sso-icon', 'tabler-shield-lock');
    }

    public function openIconPicker(): void
    {
        $this->FormIconPicker = true;
        $this->loadIcons();
    }

    public function resetIconPicker(): void
    {
        $this->FormIconPicker = false;
        $this->iconSearch = '';
        $this->filteredIcons = [];
    }

    public function clearIcon(): void
    {
        $this->ssoIcon = '';
    }

    private function getIconPath(): string
    {
        $possiblePaths = [
            base_path('vendor/secondnetwork/blade-tabler-icons/resources/svg'),
            dirname(base_path()).'/vendor/secondnetwork/blade-tabler-icons/resources/svg',
            public_path('vendor/blade-tabler-icons'),
        ];

        foreach ($possiblePaths as $path) {
            if (is_dir($path)) {
                return $path;
            }
        }

        return '';
    }

    public function loadIcons(): void
    {
        $this->filteredIcons = [];

        $iconPath = $this->getIconPath();

        if (empty($iconPath) || ! is_dir($iconPath)) {
            return;
        }

        try {
            $files = File::files($iconPath);

            if (empty($files)) {
                return;
            }

            $icons = collect($files)
                ->map(fn ($file) => str_replace('.svg', '', $file->getFilename()))
                ->sort()
                ->values();

            if ($this->iconSearch) {
                $search = strtolower(trim($this->iconSearch));
                if (! empty($search)) {
                    $icons = $icons->filter(fn ($name) => str_contains(strtolower($name), $search));
                }
            }

            $this->filteredIcons = $icons->take(100)->map(fn ($name) => [
                'id' => 'tabler-'.$name,
                'name' => $name,
                'full_name' => 'tabler-'.$name,
            ])->values()->toArray();
        } catch (\Exception $e) {
            $this->filteredIcons = [];
        }
    }

    public function updatedIconSearch(): void
    {
        $this->loadIcons();
    }

    public function selectIcon(string $name): void
    {
        $this->ssoIcon = 'tabler-'.$name;
        $this->FormIconPicker = false;
        $this->iconSearch = '';
        $this->filteredIcons = [];
    }

    public function saveLoginButton(): void
    {
        $this->authorizeAdmin();

        $validated = $this->validate([
            'ssoEnabled' => ['boolean'],
            'ssoTenantUuid' => ['nullable', 'string', 'max:255'],
            'ssoLabel' => ['nullable', 'string', 'max:255'],
            'ssoIcon' => ['nullable', 'string', 'max:255'],
        ]);

        $this->writeSetting('sso', $validated['ssoEnabled'] ? '1' : '');
        $this->writeSetting('sso-url', $validated['ssoTenantUuid'] ?? '');
        $this->writeSetting('sso-label', $validated['ssoLabel'] ?? '');
        $this->writeSetting('sso-icon', $validated['ssoIcon'] ?? '');

        Cache::forget('settings');
        $this->dispatch('saml2-button-saved');
    }

    private function writeSetting(string $key, string $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key, 'group' => 'global'],
            ['data' => $value, 'name' => ucwords(str_replace('-', ' ', $key))],
        );
    }

    public function loadTenants(): void
    {
        if (! Features::hasSaml2()) {
            $this->tenants = [];

            return;
        }

        $model = $this->tenantModel();

        $this->tenants = $model::query()
            ->orderBy('id')
            ->get()
            ->map(fn ($tenant) => [
                'id' => $tenant->id,
                'uuid' => $tenant->uuid,
                'key' => $tenant->key,
                'idp_entity_id' => $tenant->idp_entity_id,
                'idp_login_url' => $tenant->idp_login_url,
                'idp_logout_url' => $tenant->idp_logout_url,
                'relay_state_url' => $tenant->relay_state_url,
                'name_id_format' => $tenant->name_id_format,
            ])
            ->all();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        if (! Features::hasSaml2()) {
            return;
        }

        $tenant = $this->tenantModel()::find($id);

        if (! $tenant) {
            return;
        }

        $this->editingId = $tenant->id;
        $this->key = (string) $tenant->key;
        $this->idp_entity_id = (string) $tenant->idp_entity_id;
        $this->idp_login_url = (string) $tenant->idp_login_url;
        $this->idp_logout_url = (string) $tenant->idp_logout_url;
        $this->idp_x509_cert = (string) $tenant->idp_x509_cert;
        $this->relay_state_url = (string) $tenant->relay_state_url;
        $this->name_id_format = (string) ($tenant->name_id_format ?: 'persistent');
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->authorizeAdmin();

        if (! Features::hasSaml2()) {
            return;
        }

        $validated = $this->validate();

        $model = $this->tenantModel();

        if ($this->editingId !== null) {
            $tenant = $model::find($this->editingId);

            if (! $tenant) {
                return;
            }
            $tenant->update($validated);
        } else {
            $validated['uuid'] = (string) Str::uuid();
            // saml2_tenants.metadata is NOT NULL without a default; the model
            // casts it to array, so seed an empty one on create.
            $validated['metadata'] = [];
            $model::create($validated);
        }

        $this->resetForm();
        $this->loadTenants();
        $this->dispatch('saml2-saved');
    }

    /**
     * Fetch (or read) IdP SAML2 metadata and pre-fill the tenant form. Accepts
     * an uploaded XML file ($metadataFile), a metadata URL ($metadataUrl), or
     * pasted XML ($metadataXml) — checked in that order.
     */
    public function importMetadata(): void
    {
        $this->authorizeAdmin();

        if ($this->metadataFile) {
            $this->validate(['metadataFile' => ['file', 'max:2048']]);
        }

        $xml = '';

        try {
            $url = trim($this->metadataUrl);
            $paste = trim($this->metadataXml);

            if ($this->metadataFile) {
                $xml = (string) $this->metadataFile->get();
            } elseif ($url !== '') {
                if (! preg_match('#^https?://#i', $url)) {
                    $this->addError('metadataUrl', __('Enter a valid http(s) URL.'));

                    return;
                }

                $response = Http::timeout(10)->acceptJson()->withHeaders(['Accept' => 'application/xml, text/xml, */*'])->get($url);

                if (! $response->successful()) {
                    $this->addError('metadataUrl', __('Could not fetch metadata (HTTP :status).', ['status' => $response->status()]));

                    return;
                }

                $xml = $response->body();
            } elseif ($paste !== '') {
                $xml = $paste;
            } else {
                $this->addError('metadataUrl', __('Provide a metadata URL, upload an XML file, or paste the XML.'));

                return;
            }

            $parsed = $this->parseIdpMetadata($xml);
        } catch (\Throwable $e) {
            $this->addError('metadataUrl', __('Failed to parse metadata: :msg', ['msg' => $e->getMessage()]));

            return;
        }

        if ($parsed['entityId'] === '' && $parsed['loginUrl'] === '') {
            $this->addError('metadataUrl', __('No IdP data found in the metadata.'));

            return;
        }

        // Fill the form; keep existing values when the metadata omits a field.
        $this->idp_entity_id = $parsed['entityId'] ?: $this->idp_entity_id;
        $this->idp_login_url = $parsed['loginUrl'] ?: $this->idp_login_url;
        $this->idp_logout_url = $parsed['logoutUrl'] ?: $this->idp_logout_url;
        $this->idp_x509_cert = $parsed['x509cert'] ?: $this->idp_x509_cert;

        if ($this->key === '' && $parsed['entityId'] !== '') {
            $host = parse_url($parsed['entityId'], PHP_URL_HOST);
            $this->key = Str::slug($host ?: $parsed['entityId']);
        }

        $this->reset(['metadataFile', 'metadataXml']);
        $this->showForm = true;
        $this->resetValidation();
        $this->dispatch('saml2-metadata-imported');
    }

    /**
     * Parse IdP SAML2 metadata XML. Uses OneLogin's parser when the saml2
     * package ships it, otherwise a DOMXPath fallback.
     *
     * @return array{entityId:string,loginUrl:string,logoutUrl:string,x509cert:string}
     */
    private function parseIdpMetadata(string $xml): array
    {
        $result = ['entityId' => '', 'loginUrl' => '', 'logoutUrl' => '', 'x509cert' => ''];

        if (class_exists(IdPMetadataParser::class)) {
            $info = IdPMetadataParser::parseXML($xml);
            $idp = $info['idp'] ?? [];

            $result['entityId'] = (string) ($idp['entityId'] ?? '');
            $result['loginUrl'] = (string) ($idp['singleSignOnService']['url'] ?? '');
            $result['logoutUrl'] = (string) ($idp['singleLogoutService']['url'] ?? '');
            $result['x509cert'] = (string) ($idp['x509cert'] ?? '');

            if ($result['x509cert'] === '' && ! empty($idp['x509certMulti']['signing'][0])) {
                $result['x509cert'] = (string) $idp['x509certMulti']['signing'][0];
            }

            return $result;
        }

        $previous = libxml_use_internal_errors(true);

        try {
            $dom = new \DOMDocument;
            if (! $dom->loadXML($xml)) {
                throw new \RuntimeException(__('Invalid XML.'));
            }

            $xpath = new \DOMXPath($dom);
            $xpath->registerNamespace('md', 'urn:oasis:names:tc:SAML:2.0:metadata');
            $xpath->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');

            $entity = $xpath->query('//md:EntityDescriptor')->item(0);
            if ($entity instanceof \DOMElement) {
                $result['entityId'] = $entity->getAttribute('entityID');
            }

            $redirect = 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect';

            $sso = $xpath->query("//md:IDPSSODescriptor/md:SingleSignOnService[@Binding='{$redirect}']")->item(0)
                ?? $xpath->query('//md:IDPSSODescriptor/md:SingleSignOnService')->item(0);
            if ($sso instanceof \DOMElement) {
                $result['loginUrl'] = $sso->getAttribute('Location');
            }

            $slo = $xpath->query("//md:IDPSSODescriptor/md:SingleLogoutService[@Binding='{$redirect}']")->item(0)
                ?? $xpath->query('//md:IDPSSODescriptor/md:SingleLogoutService')->item(0);
            if ($slo instanceof \DOMElement) {
                $result['logoutUrl'] = $slo->getAttribute('Location');
            }

            // Prefer the signing certificate, fall back to the first cert found.
            $cert = $xpath->query("//md:IDPSSODescriptor/md:KeyDescriptor[@use='signing']//ds:X509Certificate")->item(0)
                ?? $xpath->query('//md:IDPSSODescriptor//ds:X509Certificate')->item(0);
            if ($cert instanceof \DOMNode) {
                $result['x509cert'] = preg_replace('/\s+/', '', $cert->textContent);
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        return $result;
    }

    public function delete(int $id): void
    {
        $this->authorizeAdmin();

        if (! Features::hasSaml2()) {
            return;
        }

        $this->tenantModel()::where('id', $id)->delete();

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        $this->loadTenants();
        $this->dispatch('saml2-deleted');
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingId',
            'key',
            'idp_entity_id',
            'idp_login_url',
            'idp_logout_url',
            'idp_x509_cert',
            'relay_state_url',
            'showForm',
            'metadataUrl',
            'metadataXml',
            'metadataFile',
        ]);
        $this->name_id_format = 'persistent';
        $this->resetValidation();
    }

    /**
     * The metadata/ACS/SLS endpoints the IdP needs, derived from the package's
     * routes. Returns null when the package is not installed.
     *
     * @return array{metadata:string,acs:string,sls:string,login:string,logout:string}|null
     */
    public function getEndpoints(string $tenantUuid): ?array
    {
        if (! Features::hasSaml2()) {
            return null;
        }

        return [
            'metadata' => url("/saml2/{$tenantUuid}/metadata"),
            'acs' => url("/saml2/{$tenantUuid}/acs"),
            'sls' => url("/saml2/{$tenantUuid}/sls"),
            'login' => url("/saml2/{$tenantUuid}/login"),
            'logout' => url("/saml2/{$tenantUuid}/logout"),
        ];
    }

    /**
     * @return class-string
     */
    protected function tenantModel(): string
    {
        return Tenant::class;
    }

    public function render()
    {
        return view('kompass::livewire.settings.saml2', [
            'saml2Installed' => Features::hasSaml2(),
        ]);
    }
}
