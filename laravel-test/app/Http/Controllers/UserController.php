<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tinify\Tinify;

class UserController extends Controller
{
    public function __construct()
    {

        Tinify::setKey(env('TINYPNG_API_KEY'));

    }

    public function index()
    {
        $users = User::latest()->paginate(6);
        return view('home', compact('users'));
    }


public function show($id)
{
   try {
       $user = User::findOrFail($id);
       
       // Prepare image path
       $imagePath = $user->profile_image === 'default.jpg' 
           ? asset('images/default.jpg') 
           : asset('storage/' . $user->profile_image);

       return response()->json([
           'success' => true,
           'user' => [
               'id' => $user->id,
               'name' => $user->name,
               'email' => $user->email,
               'phone' => $user->phone,
               'profile_image' => $imagePath,
               'created_at' => $user->created_at->format('Y-m-d H:i:s')
           ]
       ]);
   } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
       return response()->json([
           'success' => false,
           'message' => 'User not found'
       ], 404);
   } catch (\Exception $e) {
       return response()->json([
           'success' => false,
           'message' => 'Server error',
           'error' => $e->getMessage()
       ], 500);
   }
}

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $imageName = 'default.jpg';

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $cleanName = Str::slug($originalName);
                $imageName = $cleanName . '_' . time() . '.' . $image->getClientOriginalExtension();

                // Read the image file
                $source = \Tinify\fromFile($image->getPathname());

                // Resize and crop to 70x70
                $resized = $source->resize([
                    "method" => "cover",
                    "width" => 70,
                    "height" => 70
                ]);

                // Store the optimized image
                Storage::disk('public')->put(
                    'images/' . $imageName,
                    $resized->toBuffer()
                );

                $imageName = 'images/' . $imageName;
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'profile_image' => $imageName
            ]);

            return redirect()->back()->with('success', 'User created successfully');

        } catch (\Tinify\AccountException $e) {
            return redirect()->back()
                ->withErrors(['error' => 'TinyPNG API account error. Please check your API key.'])
                ->withInput();
        } catch (\Tinify\ClientException $e) {
            return redirect()->back()
                ->withErrors(['error' => 'TinyPNG API request failed. Please try again.'])
                ->withInput();
        } catch (\Tinify\ServerException $e) {
            return redirect()->back()
                ->withErrors(['error' => 'TinyPNG API is temporarily unavailable. Please try again later.'])
                ->withInput();
        } catch (\Tinify\ConnectionException $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Network connection error. Please check your internet connection.'])
                ->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error creating user. Please try again.'])
                ->withInput();
        }
    }
}


































// namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\User;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;

// class UserController extends Controller
// {
//     public function index()
//     {
//         $users = User::latest()->paginate(6);
//         return view('home', compact('users'));
//     }

//     public function store(Request $request)
//     {
//         try {
//             $validated = $request->validate([
//                 'name' => 'required|string|max:255',
//                 'email' => 'required|email|unique:users,email',
//                 'phone' => 'nullable|string|max:20',
//                 'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
//             ]);

//             $imageName = 'default.jpg';

//             if ($request->hasFile('profile_image')) {
//                 $image = $request->file('profile_image');
//                 $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
//                 $cleanName = Str::slug($originalName);
//                 $imageName = $cleanName . '_' . time() . '.' . $image->getClientOriginalExtension();


//                 Storage::disk('public')->putFileAs(
//                     'images',
//                     $image,
//                     $imageName
//                 );

//                 $imageName = 'images/' . $imageName;
//             }

//             $user = User::create([
//                 'name' => $validated['name'],
//                 'email' => $validated['email'],
//                 'phone' => $validated['phone'],
//                 'profile_image' => $imageName
//             ]);

//             return redirect()->back()->with('success', 'User created successfully');

//         } catch (\Illuminate\Validation\ValidationException $e) {
//             return redirect()->back()
//                 ->withErrors($e->errors())
//                 ->withInput();
//         } catch (\Exception $e) {
//             return redirect()->back()
//                 ->withErrors(['error' => 'Error creating user. Please try again.'])
//                 ->withInput();
//         }
//     }
// }
