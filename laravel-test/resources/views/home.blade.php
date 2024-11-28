<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .users-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .user-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .user-card:hover {
            transform: translateY(-5px);
        }

        .user-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: block;
            border: 3px solid #4CAF50;
        }

        .user-card p {
            margin: 5px 0;
        }

        .user-card p:first-of-type {
            font-weight: bold;
            color: #2c3e50;
        }

        .user-card p:nth-of-type(2) {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .user-card p:last-of-type {
            color: #666;
            font-size: 0.9em;
        }

        .user-form {
            max-width: 500px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .user-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .user-form input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .user-form button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.2s;
        }

        .user-form button:hover {
            background-color: #45a049;
        }

        /* Pagination Styles */
        .pagination-container {
            margin: 30px 0;
            display: flex;
            justify-content: center;
        }

        .flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 800px;
        }

        .text-sm {
            font-size: 0.875rem;
            color: #666;
        }

        .pagination span, 
        .pagination a {
            padding: 8px 12px;
            margin: 0 4px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #4CAF50;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background-color: #f8f9fa;
            border-color: #4CAF50;
        }

        [aria-current="page"] span {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .w-3 {
            width: 12px;
            height: 12px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .users-container {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>User Management System</h1>
    </div>

    <div class="container">
        <div class="users-container">
            @if($users->count() > 0)
                @foreach($users as $user)
                    <div class="user-card">
                        <img src="{{ asset($user->profile_image) }}" 
                             alt="{{ $user->name }}" 
                             onerror="this.src='{{ asset('images/default.jpg') }}'">
                        <p>{{ $user->name }}</p>
                        <p>{{ $user->email }}</p>
                        @if($user->phone)
                            <p>{{ $user->phone }}</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p>No users found.</p>
            @endif
        </div>

        <div class="pagination-container">
            {{ $users->links() }}
        </div>

        <div class="user-form">
            <h2>Add New User</h2>
           <form class="user-form" method="POST" enctype="multipart/form-data" action="{{ url('api/users') }}">
            @csrf
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone">
            <input type="file" name="profile_image" accept="image/*" required>
            <button type="submit">Add User</button>
        </form>
        </div>
    </div>
</body>
</html>