<!-- ========== Left Sidebar ========== -->
<div class="main-menu">
    <!-- Brand Logo -->
    <div class="logo-box">
        <!-- Brand Logo Light -->
        <a href="#" class="logo-light">
            <!--<img src="<?=BASE_URL?>dist/assets/images/logo.webp" alt="logo" class="logo-lg" height="50px" width="200px">-->
            <!--<img src="<?=BASE_URL?>dist/assets/images/favicon.png" alt="small logo" class="logo-sm" height="22">-->
            <h2 style="color:#fff;">Rukhsa CRM</h2>
        </a>

        <!-- Brand Logo Dark -->
        <a href="#" class="logo-dark">
            <!--<img src="<?=BASE_URL?>dist/assets/images/logo.webp" alt="dark logo" class="logo-lg" height="50px" width="200px">-->
            <!--<img src="<?=BASE_URL?>dist/assets/images/favicon.png" alt="small logo" class="logo-sm" height="22">-->
            <h2 style="color:#fff;">Rukhsa CRM</h2>
        </a>
    </div>
    
    <style>
        .disabled-link {
            pointer-events: none; /* Disables clicking */
            color: #a0a0a0; /* Gray color for disabled appearance */
            cursor: not-allowed; /* Shows a 'not allowed' cursor */
            text-decoration: none; /* Removes underline if present */
        }
        
        .disabled-link .menu-icon i {
            opacity: 0.6; /* Optional: make the icon appear disabled */
        }
        
        .disabled-link .menu-text {
            opacity: 0.6; /* Optional: make the text appear disabled */
        }

    </style>

    <!--- Menu -->
    <div data-simplebar>
        <ul class="app-menu">
            <li class="menu-title">Menu</li>
            <?php
            // Get the current page URL
            $current_page = basename($_SERVER['REQUEST_URI']);

            // Function to check if the menu item is active
            function is_active($page) {
                global $current_page;
                return $current_page == $page ? 'active' : '';
            }
            ?>
            <li class="menu-item <?php echo is_active('main.php'); ?>">
                <a href="#" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="bx bx-home-smile"></i></span>
                    <span class="menu-text"> Dashboard</span>
                </a>
            </li>

            <li class="menu-title">Leads Management</li>
            
            <li class="menu-item <?php echo is_active('search-order.php'); ?>">
                <a href="search-order.php" class="menu-link waves-effect waves-light disabled-link">
                    <span class="menu-icon"><i class="bx bx-search"></i></span>
                    <span class="menu-text"> Search Leads </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="<?=BASE_URL?>leads/create.php" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="bx bx-plus"></i></span>
                    <span class="menu-text"> Place a new Lead </span>
                </a>
            </li>
            
            <li class="menu-item <?php echo is_active('in-process.php'); ?>">
                <a href="in-process.php" class="menu-link waves-effect waves-light disabled-link">
                    <span class="menu-icon"><i class="bx bx-circle"></i></span>
                    <span class="menu-text"> In Process Leads </span>
                </a>
            </li>
            
            <li class="menu-item <?php echo is_active('completed-orders.php'); ?>">
                <a href="completed-orders.php" class="menu-link waves-effect waves-light disabled-link">
                    <span class="menu-icon"><i class="bx bx-check-circle"></i></span>
                    <span class="menu-text"> Completed Leads </span>
                </a>
            </li>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
             <li class="menu-title">Users Management</li>

            <li class="menu-item <?php echo is_active('/index.php'); ?>">
                <a href="<?=BASE_URL?>agents/index.php" class="menu-link waves-effect waves-light">
                    <span class="menu-icon"><i class="bx bx-user-plus"></i></span>
                    <span class="menu-text"> Agents </span>
                </a>
            </li>
             <li class="menu-item <?php echo is_active('apps-calendar.html'); ?>">
                <a href="<?=BASE_URL?>teams/index.php" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-group"></i></span>
                    <span class="menu-text"> Teams </span>
                </a>
            </li>
            <li class="menu-item <?php echo is_active('apps-calendar.html'); ?>">
                <a href="<?=BASE_URL?>team_leads/index.php" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-star"></i></span>
                    <span class="menu-text"> Team Leads </span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
