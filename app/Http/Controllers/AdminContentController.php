<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\Service;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    protected function types(): array
    {
        return [
            'sliders' => [
                'model' => Slider::class,
                'label' => 'Hero Sliders',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'text', 'rules' => 'required|string|max:255'],
                    'subtitle' => ['label' => 'Subtitle', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'button_text' => ['label' => 'Button Text', 'type' => 'text', 'rules' => 'nullable|string|max:120'],
                    'button_url' => ['label' => 'Button URL', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'media_type' => ['label' => 'Media Type', 'type' => 'select', 'options' => ['image' => 'Image', 'video' => 'Video'], 'rules' => 'required|in:image,video'],
                    'media_path' => ['label' => 'Media File', 'type' => 'file', 'rules' => 'nullable|file|max:20480'],
                    'sort_order' => ['label' => 'Sort Order', 'type' => 'number', 'rules' => 'nullable|integer'],
                    'active' => ['label' => 'Active', 'type' => 'checkbox', 'rules' => 'nullable|boolean'],
                ],
            ],
            'services' => [
                'model' => Service::class,
                'label' => 'Services',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'text', 'rules' => 'required|string|max:255'],
                    'slug' => ['label' => 'Slug (auto if empty)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'short_description' => ['label' => 'Short Description', 'type' => 'textarea', 'rules' => 'nullable|string|max:500'],
                    'description' => ['label' => 'Full Description', 'type' => 'textarea', 'rules' => 'nullable|string|max:5000'],
                    'icon' => ['label' => 'Icon (SVG or emoji)', 'type' => 'text', 'rules' => 'nullable|string|max:2000'],
                    'image' => ['label' => 'Image', 'type' => 'file', 'rules' => 'nullable|image|max:5120'],
                    'sort_order' => ['label' => 'Sort Order', 'type' => 'number', 'rules' => 'nullable|integer'],
                    'active' => ['label' => 'Active', 'type' => 'checkbox', 'rules' => 'nullable|boolean'],
                ],
            ],
            'team' => [
                'model' => TeamMember::class,
                'label' => 'Team Members',
                'fields' => [
                    'name' => ['label' => 'Name', 'type' => 'text', 'rules' => 'required|string|max:255'],
                    'role' => ['label' => 'Role', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'bio' => ['label' => 'Bio', 'type' => 'textarea', 'rules' => 'nullable|string|max:2000'],
                    'photo' => ['label' => 'Photo', 'type' => 'file', 'rules' => 'nullable|image|max:5120'],
                    'sort_order' => ['label' => 'Sort Order', 'type' => 'number', 'rules' => 'nullable|integer'],
                    'active' => ['label' => 'Active', 'type' => 'checkbox', 'rules' => 'nullable|boolean'],
                ],
            ],
            'testimonials' => [
                'model' => Testimonial::class,
                'label' => 'Testimonials',
                'fields' => [
                    'name' => ['label' => 'Name', 'type' => 'text', 'rules' => 'required|string|max:255'],
                    'role' => ['label' => 'Role / Company', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'quote' => ['label' => 'Quote', 'type' => 'textarea', 'rules' => 'required|string|max:2000'],
                    'avatar' => ['label' => 'Avatar', 'type' => 'file', 'rules' => 'nullable|image|max:2048'],
                    'sort_order' => ['label' => 'Sort Order', 'type' => 'number', 'rules' => 'nullable|integer'],
                    'active' => ['label' => 'Active', 'type' => 'checkbox', 'rules' => 'nullable|boolean'],
                ],
            ],
            'projects' => [
                'model' => Project::class,
                'label' => 'Projects',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'text', 'rules' => 'required|string|max:255'],
                    'slug' => ['label' => 'Slug (auto if empty)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'category' => ['label' => 'Category', 'type' => 'text', 'rules' => 'nullable|string|max:120'],
                    'location' => ['label' => 'Location', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'client_name' => ['label' => 'Client Name', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'description' => ['label' => 'Description', 'type' => 'textarea', 'rules' => 'nullable|string|max:5000'],
                    'cover_image' => ['label' => 'Cover Image', 'type' => 'file', 'rules' => 'nullable|image|max:5120'],
                    'gallery' => ['label' => 'Gallery Images', 'type' => 'files', 'rules' => 'nullable|array', 'item_rules' => 'image|max:5120'],
                    'latitude' => ['label' => 'Latitude', 'type' => 'number', 'rules' => 'nullable|numeric'],
                    'longitude' => ['label' => 'Longitude', 'type' => 'number', 'rules' => 'nullable|numeric'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['planning' => 'Planning', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'on_hold' => 'On Hold'], 'rules' => 'nullable|in:planning,ongoing,completed,on_hold'],
                    'featured' => ['label' => 'Featured', 'type' => 'checkbox', 'rules' => 'nullable|boolean'],
                    'client_ids' => ['label' => 'Tracked by Clients', 'type' => 'relation', 'rules' => 'nullable|array'],
                    'sort_order' => ['label' => 'Sort Order', 'type' => 'number', 'rules' => 'nullable|integer'],
                    'active' => ['label' => 'Active', 'type' => 'checkbox', 'rules' => 'nullable|boolean'],
                ],
            ],
        ];
    }

    protected function typeConfig(string $type): array
    {
        $types = $this->types();

        if (! isset($types[$type])) {
            abort(404);
        }

        return $types[$type];
    }

    public function index($type)
    {
        $config = $this->typeConfig($type);
        $model = $config['model'];

        $items = $model::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.content.index', compact('type', 'config', 'items'));
    }

    public function create($type)
    {
        $config = $this->typeConfig($type);
        $clients = $type === 'projects' ? User::orderBy('name')->get() : collect();

        return view('admin.content.form', compact('type', 'config', 'clients'))->with('item', null);
    }

    public function store(Request $request, $type)
    {
        $config = $this->typeConfig($type);
        $model = $config['model'];

        $data = $this->validateAndCollect($request, $config, null);

        $item = $model::create($data);

        if ($type === 'projects' && $request->has('client_ids')) {
            $item->clients()->sync($request->input('client_ids', []));
        }

        Artisan::call('view:clear');

        return redirect()->route('admin.content.index', $type)->with('success', $config['label'] . ' created.');
    }

    public function edit($type, $id)
    {
        $config = $this->typeConfig($type);
        $model = $config['model'];
        $item = $model::findOrFail($id);
        $clients = $type === 'projects' ? User::orderBy('name')->get() : collect();

        return view('admin.content.form', compact('type', 'config', 'item', 'clients'));
    }

    public function update(Request $request, $type, $id)
    {
        $config = $this->typeConfig($type);
        $model = $config['model'];
        $item = $model::findOrFail($id);

        $data = $this->validateAndCollect($request, $config, $item);

        $item->update($data);

        if ($type === 'projects') {
            $item->clients()->sync($request->input('client_ids', []));
        }

        Artisan::call('view:clear');

        return redirect()->route('admin.content.index', $type)->with('success', $config['label'] . ' updated.');
    }

    public function destroy($type, $id)
    {
        $config = $this->typeConfig($type);
        $model = $config['model'];
        $item = $model::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.content.index', $type)->with('success', $config['label'] . ' deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        $type = $request->input('type');
        $config = $this->typeConfig($type);
        $model = $config['model'];

        foreach ($request->input('order') as $position => $id) {
            $model::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['ok' => true]);
    }

    /* ----------------------------- Project updates ----------------------------- */
    public function updatesIndex($projectId)
    {
        $project = Project::findOrFail($projectId);
        $updates = $project->updates()->orderByDesc('posted_at')->get();

        return view('admin.content.project-updates', compact('project', 'updates'));
    }

    public function updateStore(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string|max:5000',
            'progress' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|max:5120',
            'posted_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeFileInPublic($request->file('image'), 'updates');
        }

        $data['posted_at'] = $data['posted_at'] ?? now();
        $data['progress'] = $data['progress'] ?? 0;

        $project->updates()->create($data);

        Artisan::call('view:clear');

        return redirect()->route('admin.content.updates', $project->id)->with('success', 'Update posted.');
    }

    public function updateDestroy($projectId, $updateId)
    {
        $update = ProjectUpdate::where('project_id', $projectId)->findOrFail($updateId);
        $update->delete();

        return redirect()->route('admin.content.updates', $projectId)->with('success', 'Update removed.');
    }

    /* ----------------------------- Helpers ----------------------------- */
    protected function validateAndCollect(Request $request, array $config, $item = null): array
    {
        $rules = [];
        $booleans = [];
        $files = [];
        $gallery = null;
        $relation = null;

        foreach ($config['fields'] as $key => $field) {
            $rules[$key] = $field['rules'];

            if ($field['type'] === 'checkbox') {
                $booleans[] = $key;
            }
            if ($field['type'] === 'file') {
                $files[$key] = $field;
            }
            if ($field['type'] === 'files') {
                $gallery = $key;
            }
            if ($field['type'] === 'relation') {
                $relation = $key;
            }
        }

        $validated = $request->validate($rules);

        $data = [];

        foreach ($config['fields'] as $key => $field) {
            if ($field['type'] === 'file') {
                if ($request->hasFile($key)) {
                    $data[$key] = $this->storeFileInPublic($request->file($key), $this->storageFolder($key));
                } elseif ($item && $item->exists) {
                    $data[$key] = $item->{$key};
                }
                continue;
            }

            if ($field['type'] === 'files') {
                if ($request->hasFile($key)) {
                    $paths = [];
                    if ($item && is_array($item->{$key})) {
                        $paths = $item->{$key};
                    }
                    foreach ($request->file($key) as $file) {
                        $paths[] = $this->storeFileInPublic($file, 'galleries');
                    }
                    $data[$key] = $paths;
                } elseif ($item && $item->exists) {
                    $data[$key] = $item->{$key};
                }
                continue;
            }

            if ($field['type'] === 'relation') {
                continue; // handled after save
            }

            if (in_array($key, $booleans, true)) {
                $data[$key] = $request->boolean($key);
                continue;
            }

            if (array_key_exists($key, $validated)) {
                $value = $validated[$key];

                if (($key === 'slug' || $key === 'category') && empty($value) && isset($validated['title'])) {
                    $value = Str::slug($validated['title']);
                }

                $data[$key] = $value;
            }
        }

        return $data;
    }

    protected function storeFileInPublic(\Illuminate\Http\UploadedFile $file, string $folder): string
    {
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $publicPath = public_path($folder);

        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        $file->move($publicPath, $fileName);

        return $folder . '/' . $fileName;
    }

    protected function storageFolder(string $key): string
    {
        return match ($key) {
            'cover_image', 'gallery' => 'projects',
            'media_path' => 'sliders',
            'image' => 'services',
            'photo' => 'team',
            'avatar' => 'testimonials',
            default => 'uploads',
        };
    }
}
