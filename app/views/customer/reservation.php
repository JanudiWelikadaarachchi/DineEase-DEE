<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/customer-styles.css">
    <link rel="icon" type="image/x-icon" href="<?php echo URLROOT ?>/public/img/login/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <title><?php echo SITENAME; ?></title>
</head>

<body>
    <div class="container">
        <div class="navbar-template">
            <nav class="navbar">
                <div class="topbar">
                    <div class="logo-item">
                        <i class="bx bx-menu" id="sidebarOpen"></i>
                        <img src="<?php echo URLROOT ?>/public/img/login/dineease-logo.svg" alt="DineEase Logo">
                        <div class="topbar-title">
                            DINE<span>EASE</span>
                        </div>
                    </div>
                    <div class="navbar-content">
                        <div class="profile-details">
                            <span class="material-symbols-outlined material-symbols-outlined-topbar ">notifications </span>
                            Hello, &nbsp; <?php echo ucfirst($_SESSION['role']) ?> <span class="user-name"> &nbsp; | &nbsp; <?php echo  $_SESSION['user_name'] ?></span>
                            <img src="<?php echo URLROOT ?>/public/img/login/profilepic.png" alt="profile-photo" class="profile" />
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="sidebar-template">
            <nav class="sidebar">
                <div class="sidebar-container">
                    <div class="menu_content">
                        <hr class='separator'>
                        <ul class="menu_items">
                            <div class="menu_title menu_menu"></div>
                            <li class="item">
                                <a href="<?php echo URLROOT ?>/customers/index" class="nav_link nav_link_switch" data-content='home'>
                                    <button class="button-sidebar-menu " id="homeButton">
                                        <span class="navlink_icon">
                                            <span class="material-symbols-outlined ">
                                                home
                                            </span>
                                        </span>
                                        <span class="button-sidebar-menu-content">Dashboard</span>
                                    </button>
                                </a>
                            </li>
                            <li class="item">
                                <a href="<?php echo URLROOT ?>/customers/reservation" class="nav_link" data-content='reservation'>
                                    <button class="button-sidebar-menu active-nav" id="reservationButton">
                                        <span class="navlink_icon">
                                            <span class="material-symbols-outlined ">
                                                book_online
                                            </span>
                                        </span>
                                        <span class="button-sidebar-menu-content">Reservation </span>
                                    </button>
                                </a>
                            </li>

                            <li class="item">
                                <a href="<?php echo URLROOT ?>/customers/menu" class="nav_link" data-content='menu'>
                                    <button class="button-sidebar-menu " id="reservationButton">
                                        <span class="navlink_icon">
                                            <span class="material-symbols-outlined ">
                                                restaurant_menu
                                            </span>
                                        </span>
                                        <span class="button-sidebar-menu-content">Menus </span>
                                    </button>
                                </a>
                            </li>

                            <li class="item">
                                <a href="<?php echo URLROOT ?>/customers/review" class="nav_link" data-content='menu'>
                                    <button class="button-sidebar-menu" id="reservationButton">
                                        <span class="navlink_icon">
                                            <span class="material-symbols-outlined ">
                                                reviews
                                            </span>
                                        </span>
                                        <span class="button-sidebar-menu-content">Reviews </span>
                                    </button>
                                </a>
                            </li>
                            <!-- End -->


                        </ul>
                        <hr class='separator'>

                        <ul class="menu_items">
                            <div class="menu_title menu_user"></div>



                            <li class="item">
                                <a href="<?php echo URLROOT ?>/customers/profile" class="nav_link" data-content='menu'>
                                    <button class="button-sidebar-menu" id="reservationButton">
                                        <span class="navlink_icon">
                                            <span class="material-symbols-outlined ">
                                                account_circle
                                            </span>
                                        </span>
                                        <span class="button-sidebar-menu-content">My Account </span>
                                    </button>
                                </a>
                            </li>
                            <li class="item">
                                <a href="<?php echo URLROOT; ?>/users/logout" class="nav_link">
                                    <button class="button-sidebar-menu">
                                        <span class="navlink_icon">
                                            <span class="material-symbols-outlined ">
                                                logout
                                            </span>
                                        </span>
                                        <span class="button-sidebar-menu-content">Logout</span>
                                    </button>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="body-template">
            <div id="content">
                <div class="reservation-container">
                    <div class="tabset">
                        <input type="radio" name="tabset" id="tab1" aria-controls="view" checked>
                        <label for="tab1">View Reservations</label>
                        <input type="radio" name="tabset" id="tab2" aria-controls="add">
                        <label for="tab2">Add Reservation</label>

                        <div class="tab-panels">
                            <section id="view" class="tab-panel">
                                <div class="content read">
                                    <h2>View Reservations</h2>
                                    <div class="searchnfilter">
                                        <!-- Search Form -->
                                        <div class="search-reservation">
                                            <form class="search-form" method="POST" action="<?php echo URLROOT; ?>/customers/reservation">
                                                <input type="text" name="search" placeholder="Search reservations" value="<?php echo $data['search'] ?>">
                                                <button type="submit">Search</button>
                                            </form>
                                        </div>
                                        <div class="filter-reservation">
                                            <form id="reservationFilters" action="<?php echo URLROOT; ?>/customers/reservation" method="POST">
                                                <select name="status">
                                                    <option value="">Select Status</option>
                                                    <?php foreach ($data['reservationStatus'] as $status) : ?>
                                                        <option value="<?php echo $status->status ?>" <?php if (strtoupper($data['status']) == $status->status) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?php echo $status->status ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <input type="date" name="startDate" value="<?php if (isset($data['startDate'])) {
                                                                                                echo $data['startDate'];
                                                                                            } ?>">
                                                <input type="date" name="endDate" value="<?php if (isset($data['endDate'])) {
                                                                                                echo $data['endDate'];
                                                                                            } ?>">
                                                <button type="submit">Filter</button>
                                            </form>
                                        </div>
                                    </div>

                                    <table>
                                        <thead>
                                            <tr>
                                                <td class="long-td">Date</td>
                                                <td class="long-td">Start Time</td>
                                                <td class="long-td">End Time</td>
                                                <td>No of People</td>
                                                <td class="long-td">Amount</td>
                                                <td class="long-td">Status</td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                        <tbody>


                                            <?php foreach ($data['reservations'] as $index => $reservation) { ?>
                                                <tr>
                                                    <td><?php echo $reservation->date ?></td>
                                                    <td><?php echo $reservation->reservationStartTime  ?></td>
                                                    <td><?php echo $reservation->reservationEndTime  ?></td>
                                                    <td><?php echo $reservation->numOfPeople ?></td>
                                                    <td>Rs. <?php echo $reservation->amount ?>.00</td>
                                                    <td><?php echo $reservation->status ?></td>
                                                    <td class="actions">
                                                        <a href="<?php echo URLROOT; ?>/Customers/cancelReservation/<?php echo $reservation->reservationID ?>" class="trash <?php echo ($reservation->status == 'Cancelled' ? 'disabled-button' : ''); ?>" onclick="return confirm('Are you sure you want to cancel this reservation?');"><i class="fas fa-trash fa-xs"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <!-- // TODO #19 : Filters does not apply when the page is reloaded while navigating through pages -->
                                    <!-- Pagination Links -->
                                    <div class="pagination-view">
                                        <?php if ($data['page'] > 1) : ?>
                                            <a href="?page=<?php echo $data['page'] - 1; ?>">&laquo;</a>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $data['totalPages']; $i++) : ?>
                                            <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $data['page'] ? 'active' : ''; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        <?php endfor; ?>

                                        <?php if ($data['page'] < $data['totalPages']) : ?>
                                            <a href="?page=<?php echo $data['page'] + 1; ?>">&raquo;</a>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </section>
                            <section id="add" class="tab-panel">
                                <div class="add-reservation-container">
                                    <div class="reservation-container-fluid">
                                        <div class=" text-center ">
                                            <div class="card">
                                                <h2 id="heading" class="text-center">Reserve Slot</h2>
                                                <form id="msform" class="msform-container" action="<?php echo URLROOT; ?>/customers/addReservation" method="post">
                                                    <div class="prog">
                                                        <ul id="progressbar">
                                                            <li class="active" id="package"><strong>Suite</strong></li>
                                                            <li id="rd"><strong>Reservation Details</strong></li>
                                                            <li id="availability"><strong>Availability</strong></li>
                                                            <li id="confirm"><strong>Payment</strong></li>
                                                        </ul>
                                                    </div>

                                                    <fieldset>
                                                        <div class="form-card">
                                                            <input type="text" hidden id="customerID" value="<?php echo ($_SESSION['user_id']) ?>"></input>
                                                            <div class="row fixed-height-row-reservation">
                                                                <div>
                                                                    <h3 class="fs-title">Select the Suite:</h3>
                                                                </div>
                                                                <div class="suit-cards">
                                                                    <div class="wrapper">
                                                                        <div class="card">
                                                                            <div class="poster"><img src="<?php echo URLROOT; ?>/img/Packages/pk1.jpg" alt=""></div>
                                                                            <div class="details">
                                                                                <div class="up">
                                                                                    <span class="material-symbols-outlined">
                                                                                        stat_2
                                                                                    </span>
                                                                                    <div class=" suitTag">BUDGET </div>
                                                                                </div>
                                                                                <h1>Budget</h1>

                                                                                <div class="rating">

                                                                                    <?php echo str_repeat('<span class="material-symbols-outlined" style="color: green;">star</span>', 4) . str_repeat('<span class="material-symbols-outlined">star</span>', 1); ?>
                                                                                    <span>4/5 ( 20 ) </span>
                                                                                </div>

                                                                                <p class="desc">
                                                                                    -Comfortable and affordable dining experience <br>
                                                                                    -Cozy seating area <br>
                                                                                    -Pleasant ambiance <br>
                                                                                    -Suitable for dining alone or with a group <br>
                                                                                    -<b>LKR.500.00/seat for reservations</b> </p>
                                                                                <div class="btn goToReviews"> Go to Reviews </div>

                                                                            </div>
                                                                        </div>

                                                                        <div class="card">
                                                                            <div class="poster"><img src="<?php echo URLROOT; ?>/img/Packages/pk2.jpg" alt=""></div>
                                                                            <div class="details">
                                                                                <div class="up">
                                                                                    <span class="material-symbols-outlined">
                                                                                        stat_2
                                                                                    </span>
                                                                                    <div class=" suitTag"> GOLD</div>
                                                                                </div>
                                                                                <h1>Gold</h1>

                                                                                <div class="rating">

                                                                                    <?php echo str_repeat('<span class="material-symbols-outlined" style="color: green;">star</span>', 5); ?>
                                                                                    <span>5/5 ( 20 ) </span>
                                                                                </div>

                                                                                <p class="desc">

                                                                                    - Elegant dining experience <br>
                                                                                    - Spacious seating area <br>
                                                                                    - Suitable for special occasions or celebrations <br>
                                                                                    - <b>LKR.500.00/seat for reservations<br>
                                                                                        - 5% tax on total bill <br></b>
                                                                                </p>
                                                                                <div class="btn goToReviews"> Go to Reviews </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="card">
                                                                            <div class="poster"><img src="<?php echo URLROOT; ?>/img/Packages/pk3.jpeg" alt=""></div>
                                                                            <div class="details">
                                                                                <div class="up">
                                                                                    <span class="material-symbols-outlined">
                                                                                        stat_2
                                                                                    </span>
                                                                                    <div class=" suitTag"> PLATINUM </div>
                                                                                </div>
                                                                                <h1>Platinum</h1>

                                                                                <div class="rating">

                                                                                    <?php echo str_repeat('<span class="material-symbols-outlined" style="color: green;">star</span>', 5); ?>
                                                                                    <span>5/5 ( 20 ) </span>
                                                                                </div>

                                                                                <p class="desc">
                                                                                    -Luxurious dining experience <br>
                                                                                    -Suitable for special occasions or celebrations <br>
                                                                                    -Full suite booking <br>
                                                                                    -<b>LKR.6000.00 for full suite booking <br>
                                                                                        - 10% tax on total bill <br> </b></p>
                                                                                <div class="btn goToReviews"> Go to Reviews </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="pkg-selection">
                                                                    <div class="radio-inputs">
                                                                        <label class="radio">
                                                                            <input type="radio" id="packageID" value="1" name="packageID" checked>
                                                                            <span class="name">Budget</span>
                                                                        </label>
                                                                        <label class="radio">
                                                                            <input type="radio" id="packageID" value="2" name="packageID">
                                                                            <span class="name">Gold</span>
                                                                        </label>
                                                                        <label class="radio">
                                                                            <input type="radio" id="packageID" value="3" name="packageID">
                                                                            <span class="name">Platinum</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="button" name="next" class="next action-button" value="Next" />
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="form-card">
                                                            <div class="row">
                                                                <div class="fixed-height-row-reservation">
                                                                    <h3 class="fs-title">Select Date and No of People:</h3>
                                                                    <div class="dp-container">

                                                                        <div class="date-slots">
                                                                            <?php
                                                                            date_default_timezone_set("Asia/Calcutta");
                                                                            $currentDate = strtotime(date("Y-m-d"));

                                                                            for ($i = 0; $i < 15; $i++) {
                                                                                $date = date("Y-m-d", strtotime("+{$i} days", $currentDate));
                                                                                $selectedClass = $i == 0 ? "selected" : "";
                                                                                echo "<div class='date-slot {$selectedClass}' id='dateToCheck' data-date='{$date}'>" . date('d M', strtotime($date)) . "</div>";
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <input type="hidden" id="selectedDate" name="date" value="<?= date("Y-m-d") ?>">


                                                                        <div class="people-selection">
                                                                            <label for="numOfPeople" class="slots">Number of People:</label>
                                                                            <br>
                                                                            <div class="people-icons">
                                                                                <?php for ($i = 1; $i <= 10; $i++) : ?>
                                                                                    <div class="person-icon <?= $i == 1 ? 'selected' : '' ?>" data-value="<?= $i ?>">
                                                                                        <i class="fa-solid fa-person" style="font-size:50px"></i>
                                                                                        <p><?= $i ?></p>
                                                                                    </div>
                                                                                <?php endfor; ?>
                                                                            </div>
                                                                            <input type="hidden" id="numOfPeople" name="numOfPeople" value="1">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="button" name="next" class="next action-button" id="checkSlots" value="Next" />
                                                        <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="form-card">
                                                            <div class="row fixed-height-row-reservation">
                                                                <div>
                                                                    <h3 class="fs-title">Select the Time Slot:</h3>
                                                                </div>

                                                                <div class="availability-table">
                                                                    <div class="av-table">

                                                                        <div class="time-slots" id="time-slots">
                                                                        </div>
                                                                        <input type="hidden" id="selectedTime" name="reservationStartTime" value="08:00">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <input type="button" name="next" class="next action-button" value="Next" />
                                                        <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="form-card">
                                                            <div class="row fixed-height-row-reservation">
                                                                <div class="reservation-summary">
                                                                    <h3 class="fs-title">Thank you for your reservation</h3>
                                                                    <div class="summary-details">
                                                                        <div class="summery-row left">
                                                                            <p>Date: <span id="summary-date"></span></p>
                                                                            <p>No of people: <span id="summary-people"></span></p>
                                                                            <p>Time: <span id="summary-time"></span></p>
                                                                        </div>
                                                                        <div class="summery-row right">
                                                                            <p>Suite: <span id="summary-package"></span></p>
                                                                            <p>Table: <span id="summary-table"></span></p>
                                                                            <p class="sum-amount">Total Amount: <span id="total-amount"></span></p>
                                                                            <input type="hidden" id="totalAmount" name="amount" value="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="added-items">
                                                                        <div class="menu-items" id="menu-items-list">
                                                                            <!-- Menu items will be added here dynamically -->
                                                                        </div>
                                                                        <button type="button" id="view-food-menu-in-cart">+ Add Food Items</button>

                                                                        <div id="menu-div-purchase" class="menu-div-purchase">
                                                                            <div class="btn close-menu-div-purchase " id="close-menu-div-purchase"> <span class="material-symbols-outlined">
                                                                                    close
                                                                                </span></div>
                                                                            <div class="customer-menu-view">
                                                                                <div class="menu-view-header-bar">
                                                                                    <div class="menu-view-filters">
                                                                                        <div class="menu-categories">
                                                                                            <div class="category-button active-category" data-category-id="all">All</div>
                                                                                            <div class="category-button" data-category-id="1"><span class="material-symbols-outlined">
                                                                                                    fastfood
                                                                                                </span></div>
                                                                                            <div class="category-button" data-category-id="2"><span class="material-symbols-outlined">
                                                                                                    dinner_dining
                                                                                                </span></div>
                                                                                            <div class="category-button" data-category-id="3"><span class="material-symbols-outlined">
                                                                                                    tapas
                                                                                                </span></div>
                                                                                            <div class="category-button" data-category-id="4"><span class="material-symbols-outlined">
                                                                                                    soup_kitchen
                                                                                                </span></div>
                                                                                            <div class="category-button" data-category-id="5"><span class="material-symbols-outlined">
                                                                                                    rice_bowl
                                                                                                </span></div>
                                                                                            <div class="category-button" data-category-id="6"><span class="material-symbols-outlined">
                                                                                                    outdoor_grill
                                                                                                </span></div>
                                                                                            <div class="category-button" data-category-id="7"><span class="material-symbols-outlined">
                                                                                                    hotel_class
                                                                                                </span></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="menu-view-head">
                                                                                        <div class="search-reservation hide">
                                                                                            <form class="search-form hide" method="GET" action="">
                                                                                                <input type="text" name="search" placeholder="Search Menu Item" value="" id="search-input">
                                                                                                <button type="submit" id="search-button">Search</button>
                                                                                            </form>
                                                                                        </div>
                                                                                        <div class="menu-filters">
                                                                                            <div class="price-filter">

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="menu-box">
                                                                                        <div class="menu-items">
                                                                                            <div id="menu-container" class="menu-container-div-out">

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="pagination-container">
                                                                                        <div class="pagination-view-only-menu">
                                                                                            <div class="pgbtn" id="prev-page">Previous</div>
                                                                                            <span id="page-info"></span>
                                                                                            //TODO: Pagination does not stop at maximum no of pages
                                                                                            <div class="pgbtn" id="next-page">Next</div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <input type="hidden" id="tableID" name="tableID" value="1">
                                                                    </div>
                                                                    <input class=""  type="button" id="proceed-to-pay" type="submit" value="Proceed to Pay"></input>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo URLROOT; ?>/js/jquery-3.7.1.js"></script>
    <script src="<?php echo URLROOT; ?>/js/customer.js"></script>
    <script src="<?php echo URLROOT; ?>/js/cart.js"></script>
    <script src="<?php echo URLROOT; ?>/js/customer-reservation.js"></script>
    <script src="<?php echo URLROOT; ?>/js/customer-menu.js"></script>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

</body>

</html>