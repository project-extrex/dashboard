<?php
/**
 * Login View Template
 */
?>
<div class="auth-form login-form">
    <h2>Welcome Back</h2>
    <p class="auth-subtitle">Sign in to continue to Dashboard</p>

    <form action="<?= \App\Helper\Theme::url('auth/login') ?>" method="POST" class="needs-validation">
        <div class="form-group">
            <label for="username">Username or Email</label>
            <input type="text" 
                   class="form-control" 
                   id="username" 
                   name="username" 
                   required 
                   value="<?= htmlspecialchars($username ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-input">
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password" 
                       required>
                <button type="button" class="toggle-password" data-target="password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>

        <div class="auth-links">
            <a href="<?= \App\Helper\Theme::url('auth/forgot-password') ?>" class="forgot-password">
                Forgot Password?
            </a>
            <a href="<?= \App\Helper\Theme::url('auth/register') ?>" class="register-link">
                Create Account
            </a>
        </div>
    </form>
</div>
