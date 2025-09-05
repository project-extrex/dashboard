<?php
/**
 * Register View Template
 */
?>
<div class="auth-form register-form">
    <h2>Create Account</h2>
    <p class="auth-subtitle">Fill in your details to get started</p>

    <form action="<?= \App\Helper\Theme::url('auth/register') ?>" method="POST" class="needs-validation">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" 
                   class="form-control" 
                   id="username" 
                   name="username" 
                   required 
                   pattern="[a-zA-Z0-9_]{3,20}"
                   title="Username must be between 3-20 characters and can only contain letters, numbers, and underscores"
                   value="<?= htmlspecialchars($username ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" 
                   class="form-control" 
                   id="email" 
                   name="email" 
                   required
                   value="<?= htmlspecialchars($email ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-input">
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password" 
                       required
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                       title="Must contain at least one number, one uppercase and lowercase letter, and at least 8 characters">
                <button type="button" class="toggle-password" data-target="password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirm Password</label>
            <div class="password-input">
                <input type="password" 
                       class="form-control" 
                       id="password_confirm" 
                       name="password_confirm" 
                       required>
                <button type="button" class="toggle-password" data-target="password_confirm">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
            <label class="form-check-label" for="terms">
                I agree to the <a href="<?= \App\Helper\Theme::url('terms') ?>" target="_blank">Terms of Service</a>
                and <a href="<?= \App\Helper\Theme::url('privacy') ?>" target="_blank">Privacy Policy</a>
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </div>

        <div class="auth-links">
            <span>Already have an account?</span>
            <a href="<?= \App\Helper\Theme::url('auth/login') ?>" class="login-link">
                Sign In
            </a>
        </div>
    </form>
</div>
