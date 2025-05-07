<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CategoryCreatedNotification;
use App\Mail\CategoryDeletedNotification;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }


    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'color' => 'nullable|string|max:255',
        'icon' => 'nullable|string|max:255',
        'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);


    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('categories', 'public');
    }

    $category = Category::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'description' => $request->description,
        'color' => $request->color,
        'icon' => $request->icon,
        'image' => $imagePath,
    ]);

    $merchants = User::where('user_type', 'merchant')->get();
    foreach ($merchants as $merchant) {
        Mail::to($merchant->email)->send(new CategoryCreatedNotification($category));
    }

    return redirect()->route('admin.categories.index')->with('success', 'Category created and all merchants notified.');
}


    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
        ];


        if ($request->hasFile('image')) {

            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }


            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }



    public function destroy(Category $category)
    {
       
        $merchants = User::where('user_type', 'merchant')->get();

        foreach ($merchants as $merchant) {
            Mail::to($merchant->email)->send(new CategoryDeletedNotification($category));
        }


        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted and all merchants notified.');
    }

}
