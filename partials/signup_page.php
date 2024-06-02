<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Login / Signup</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../style/style.css">

    <style>

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    margin: 0;
}


.container {
    background-color: #fff;
    padding: 40px;
    border-radius: 15px; 
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); 
    width: 400px; 
}


legend {
    font-size: 28px; 
    font-weight: 600; 
    color: #212529; 
    margin-bottom: 30px;
}


label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500; 
    color: #555;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 15px; 
    border: 1px solid #ced4da; 
    border-radius: 8px; 
    box-sizing: border-box;
    margin-bottom: 20px; 
    transition: border-color 0.2s, box-shadow 0.2s; 
}

input[type="text"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #007bff; 
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); 
}


button[type="submit"] {
    background-color: #007bff; 
    color: white;
    padding: 14px 25px; 
    border: none;
    border-radius: 8px; 
    cursor: pointer;
    font-size: 18px; 
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3; 
}


#toggleFormButton {
    background: none;
    border: none;
    color: #007bff; 
    text-decoration: underline;
    cursor: pointer;
    font-size: 16px;
    margin-top: 15px;
}


.error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 8px; 
    display: none; 
}


input[type="checkbox"] {
    margin-right: 5px; 
    accent-color: #007bff; 
}


#confirmPasswordContainer {
    display: none; 
}


.back-button {
    display: block;
    margin: 20px auto 0; 
    padding: 8px 15px;
    background-color: #ccc; 
    color: #333; 
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #999; 
}



</style>
</head>
<body>
<div class="container">

        <form id="loginForm" method="POST" action="/partials/login.php">
            <fieldset>
                <legend>Login</legend>

                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div id="loginError" class="error-message"></div>
                <input type="hidden" id="errorMessage" name="errorMessage" value="<?php echo isset($_SESSION['error_message']) ? $_SESSION['error_message'] : ''; ?>"> 
                <button type="submit" name="action" value="login">Login</button>
            </fieldset>
        </form>

        <form id="signupForm" method="POST" action="/partials/signup.php" style="display: none;">
            <fieldset>
                <legend>Create an Account</legend>
               
                <div>
                    <label for="newUsername">Username:</label>
                    <input type="text" id="newUsername" name="newUsername" placeholder="Username" required>
                </div>
                <div>
                    <label for="newPassword">Password:</label>
                    <input type="password" id="newPassword" name="newPassword" placeholder="Password" required>
                    <div id="passwordErrorMessage" class="error-message"></div>
                </div>
                <div id="confirmPasswordContainer"> 
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                    <div id="confirmPasswordErrorMessage" class="error-message"></div>
                </div>
                <div id="signupErrorMessage" class="error-message"></div>

                <button type="submit">Signup</button>
            </fieldset>
        </form>

        <a href="../index.php" class="back-button">Späť na hlavnú stránku</a>

        <button id="toggleFormButton" type="button">Don't have an account? Sign up!</button>
    </div>
    
    <script src="main.js"></script> 

</body>
</html>
