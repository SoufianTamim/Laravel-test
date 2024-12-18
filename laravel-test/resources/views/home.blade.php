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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .user-card:hover {
            transform: translateY(-5px);
        }

        /* .user-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: block;
            border: 3px solid #4CAF50;
            object-fit: cover;
        } */

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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        /* Success and Error Messages */
        .success-message {
            color: #4CAF50;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .error-message {
            color: #f44336;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Pagination Styles */
        .pagination-container {
            margin: 30px 0;
            display: flex;
            justify-content: center;
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

        /* File Input Styling */
        input[type="file"] {
            padding: 8px;
            background-color: #f8f9fa;
        }

        input[type="file"]::-webkit-file-upload-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        input[type="file"]::-webkit-file-upload-button:hover {
            background-color: #45a049;
        }


        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        .user-details {
            padding: 20px;
        }

        .user-card {
            cursor: pointer;
        }
    </style>

    <script>
        function showUserDetails(userId) {
            // First, show the modal
            const modal = document.getElementById('userModal');
            modal.style.display = 'flex';

            // Then fetch the data
            fetch(`/api/users/${userId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        const details = `
                        <div class="user-details">
                            <img src="${user.profile_image}" 
                                 alt="${user.name}" 
                                 style=" margin-bottom: 15px; object-fit: cover;">
                            <h2>${user.name}</h2>
                            <p><strong>Id :</strong> ${user.id}</p>
                            <p><strong>Email:</strong> ${user.email}</p>
                            <p><strong>Phone:</strong> ${user.phone || 'Not provided'}</p>
                            <p><strong>Created:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                        </div>
                    `;
                        document.getElementById('userDetails').innerHTML = details;
                    }
                })
                .catch(error => {
                    document.getElementById('userDetails').innerHTML = '<p style="color: red;">Error loading user details</p>';
                    console.error('Error:', error);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('userModal');
            const closeBtn = document.getElementsByClassName('close')[0];

            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });
    </script>

</head>

<body>
    <div class="header">
        <h1>User Management System</h1>
    </div>







    <div class="container">
        <div class="users-container">
            @if (isset($users) && $users->isNotEmpty())
                @forelse($users as $user)
                    <div class="user-card" onclick="showUserDetails({{ $user->id }})">
                        @php
                            $imagePath = $user->profile_image === 'default.jpg' ? '/images/default.jpg' : '/storage/' . $user->profile_image;
                        @endphp
                        <img src="{{ asset($imagePath) }}" alt="{{ $user->name }}" onerror="this.onerror=null; this.src='{{ asset('images/default.jpg') }}'">
                        <p>{{ $user->name }}</p>
                        <p>{{ $user->email }}</p>
                        @if ($user->phone)
                            <p>{{ $user->phone }}</p>
                        @endif
                    </div>
                @empty
                    <p>No users found.</p>
                @endforelse
            @else
                <p>No users available.</p>
            @endif
        </div>

        @if (isset($users) && $users->hasPages())
            <div class="pagination-container">
                {{ $users->links() }}
            </div>
        @endif

        <div class="user-form">
            <h2>Add New User</h2>
            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="error-message">{{ $errors->first() }}</div>
            @endif
            <form class="user-form" method="POST" enctype="multipart/form-data" action="{{ route('users.store') }}">
                @csrf
                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}">
                <input type="file" name="profile_image" accept="image/*" required>
                <button type="submit">Add User</button>
            </form>
        </div>
    </div>

    <!-- Add the modal structure -->
    <div id="userModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="userDetails"></div>
        </div>
    </div>


</body>

</html>
