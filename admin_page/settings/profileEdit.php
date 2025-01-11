<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Your Profile</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #fdf1f3;
            margin: 0;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 2rem;
        }

        h1 {
            color: #dc3545;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: none;
            background-color: white;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .password-section {
            margin-top: 2rem;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        button {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn-cancel {
            background-color: transparent;
            color: #333;
        }

        .btn-save {
            background-color: #dc3545;
            color: white;
        }

        .btn-save:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Your Profile</h1>
        <form>
            <div class="form-group">
                <label for="storeName">Store Name</label>
                <input type="text" id="storeName" value="MQ Kitchen">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="MQkitchen@gmail.com">
            </div>

            <div class="password-section">
                <label>Password Changes</label>
                <div class="form-group">
                    <input type="password" id="currentPassword" placeholder="Current Password">
                </div>
                <div class="form-group">
                    <input type="password" id="newPassword" placeholder="New Password">
                </div>
                <div class="form-group">
                    <input type="password" id="confirmPassword" placeholder="Confirm New Password">
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>