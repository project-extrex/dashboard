
    <style>
        form {
            background-color: #fff;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 1.5em;
        }
        label {
            display: block;
            margin-bottom: 0.5em;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Ensures padding doesn't affect the total width */
            font-size: 1em;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
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
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .password-section {
            display: flex;
            flex-direction: column;
        }
        .password-section div {
            margin-bottom: 1.5em;
        }
    </style>
    <form>
        <h2>Your Profile</h2>
        <div class="form-group">
            <label for="name">Name: </label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user_data->getName())?>">
        </div>
        <div class="form-group">
            <label for="username">Username: </label>
            <input type="text" id="username" name="username">
        </div>
        <div class="form-group">
            <label for="firstname">Firstname: </label>
            <input type="text" id="firstname" name="firstname">
        </div>
        <div class="form-group">
            <label for="lastname">Lastname: </label>
            <input type="text" id="lastname" name="lastname">
        </div>
        <div class="form-group">
            <label for="email">Email: </label>
            <input type="email" id="email" name="email">
        </div>
        <div class="password-section">
            <div class="form-group">
                <label for="current-password">Current password: </label>
                <input type="password" id="current-password" name="current-password">
            </div>
            <div class="form-group">
                <label for="new-password">New password: </label>
                <input type="password" id="new-password" name="new-password">
            </div>
            <div class="form-group">
                <label for="retype-password">Re-type password: </label>
                <input type="password" id="retype-password" name="retype-password">
            </div>
        </div>
        <input type="submit" value="Submit">
    </form>
