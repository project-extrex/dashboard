<style>
    form {
        background-color: #fff;
        padding: 2em;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }
    h2 { text-align: center; color: #333; }
    .form-group { margin-bottom: 1.5em; }
    label { display: block; margin-bottom: 0.5em; color: #555; font-weight: bold; }
    label.required::after { content: " *"; color: red; }
    input[type="text"], input[type="email"], input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 1em;
    }
    input:focus { outline: none; border-color: #007BFF; box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); }
    input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 1em;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover { background-color: #0056b3; }
    .password-section { display: flex; flex-direction: column; }
    .password-section div { margin-bottom: 1.5em; }
    .error {
        background: red;
        color: white;
        padding: 10px;
        margin: 2px;
        border-radius: 5px;
        justify-self: center;
    }
</style>

{{-- Error message --}}
@if(!empty($error))
    <div class="error">Error: {{ $error }}</div>
@endif

{{-- Profile form --}}
<form method="POST">
    
    <h2>Your Profile</h2>

    <div class="form-group">
        <label for="name" class="required">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user_data->getName()) }}" required>
    </div>

    <div class="form-group">
        <label for="username" class="required">Username:</label>
        <input type="text" id="username" name="username" value="{{ old('username', $user_data->getUsername()) }}" required>
    </div>

    <div class="form-group">
        <label for="firstname" class="required">Firstname:</label>
        <input type="text" id="firstname" name="firstname" value="{{ old('firstname', $user_data->getFirstname()) }}" required>
    </div>

    <div class="form-group">
        <label for="lastname" class="required">Lastname:</label>
        <input type="text" id="lastname" name="lastname" value="{{ old('lastname', $user_data->getLastname()) }}" required>
    </div>

    <div class="form-group">
        <label for="email" class="required">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user_data->getEmail()) }}" required>
    </div>

    <div class="password-section">
        <div class="form-group">
            <label for="password" class="required">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password for confirmation" required>
        </div>
    </div>

    <input type="hidden" name="action" value="account">
    <input type="submit" value="Submit">
</form>

{{-- Change password form --}}
<form method="POST">
    
    <div class="password-section form-group">
        <div class="form-group">
            <label for="current-password" class="required">Current password:</label>
            <input type="password" id="current-password" name="current-password" required>
        </div>

        <div class="form-group">
            <label for="new-password" class="required">New password:</label>
            <input type="password" id="new-password" name="new-password" required>
        </div>

        <div class="form-group">
            <label for="retype-password" class="required">Re-type password:</label>
            <input type="password" id="retype-password" name="retype-password" required>
        </div>
    </div>

    <input type="hidden" name="action" value="password">
    <input type="submit" value="Submit">
</form>