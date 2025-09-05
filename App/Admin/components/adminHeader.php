
<div class="admin-header">
  <div class="left">
    <div class="dropdown">
      <a href="#">Extrax ▾</a>
      <div class="dropdown-content">
        <?php

        if (isset($adminView)) {
          ?>
           <a href="/dashboard ">Visit  Dashboard  </a>
          <?php
        }

        ?>
        <a href="#">About Extrax</a>
        <a href="#">Documentation</a>
        <a href="#">Support</a>
      </div>
      </div>
    <?php if(!isset($adminView)): ?>
    <a href="/admin">Dashboard</a>
    <?php endif; ?>
    <a href="/admin/theme/">Themes</a>
    <a href="#">Plugins</a>
  </div>
  <div class="right">
    <div class="dropdown">
      <a href="#">Howdy, <?= $user->getName() ?> ▾</a>
      <div class="dropdown-content" style="right:0; left:auto;">
        <a href="#">Profile</a>
        <a href="/logout">Log Out</a>
      </div>
    </div>
  </div>
</div>

<style>
  .admin-header {
    position: sticky;
    top: -10px;
    left: 0;
    right: 0;
    z-index: 9999;
    background: #23282d;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    height: 32px;
    font-size: 13px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.4);
    font-family: Arial, sans-serif;
  }

  .admin-header .left,
  .admin-header .right {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .admin-header a {
    color: #fff;
    text-decoration: none;
    line-height: 32px;
  }

  .admin-header a:hover {
    color: #00b9eb;
  }

  /* Dropdown style */
  .dropdown {
    position: relative;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    top: 32px;
    left: 0;
    background: #32373c;
    min-width: 160px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.5);
  }

  .dropdown-content a {
    display: block;
    padding: 8px 12px;
    color: #fff;
    white-space: nowrap;
  }

  .dropdown-content a:hover {
    background: #191e23;
  }

  .dropdown:hover .dropdown-content {
    display: block;
  }
</style>