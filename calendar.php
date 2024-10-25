<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calendar with Date Search</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="calendar-container">
        <!-- Search Form (for input in day/month/year format) -->
        <form method="GET" class="search-form">
            <input type="text" name="search_date" placeholder="Enter date (dd/mm/yyyy)" required>
            <button type="submit">Search</button>
        </form>

        <div class="calendar-header">
            <?php
            // Determine the current month and year from either the search or URL query
            if (isset($_GET['month']) && isset($_GET['year'])) {
                $month = $_GET['month'];
                $year = $_GET['year'];
            } else {
                $month = date('m');  // Current month
                $year = date('Y');   // Current year
            }

            // Day to highlight in the calendar
            $day_to_highlight = null;

            // Get today's date
            $today_day = date('d');
            $today_month = date('m');
            $today_year = date('Y');

            // Handle the date search input (dd/mm/yyyy)
            if (isset($_GET['search_date'])) {
                $search_input = trim($_GET['search_date']);
                // Validate the input format (dd/mm/yyyy)
                if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $search_input, $matches)) {
                    $day_to_highlight = $matches[1];  // Extract day
                    $month = $matches[2];             // Extract month
                    $year = $matches[3];              // Extract year
                } else {
                    echo '<p class="error">Invalid date format. Please enter a valid date (dd/mm/yyyy).</p>';
                }
            } else {
                // If no search date is provided and the displayed month/year is the current one, highlight today
                if ($month == $today_month && $year == $today_year) {
                    $day_to_highlight = $today_day; // Set today as the day to highlight
                }
            }

            // Previous and next month logic
            $prev_month = $month - 1;
            $next_month = $month + 1;
            $prev_year = $year;
            $next_year = $year;

            if ($prev_month == 0) {
                $prev_month = 12;
                $prev_year--;
            }

            if ($next_month == 13) {
                $next_month = 1;
                $next_year++;
            }

            // Display the month and year with navigation buttons
            $month_names = ["January", "February", "March", "April", "May", "June", 
                            "July", "August", "September", "October", "November", "December"];
            echo '<button><a href="?month=' . $prev_month . '&year=' . $prev_year . '">◄</a></button>';
            echo '<h2>' . $month_names[$month - 1] . ' ' . $year . '</h2>';
            echo '<button><a href="?month=' . $next_month . '&year=' . $next_year . '">►</a></button>';
            ?>
        </div>

        <div class="calendar-grid">
            <div class="day">Sun</div>
            <div class="day">Mon</div>
            <div class="day">Tue</div>
            <div class="day">Wed</div>
            <div class="day">Thu</div>
            <div class="day">Fri</div>
            <div class="day">Sat</div>

            <?php
            // Get the first day of the month
            $first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
            $total_days_of_month = date('t', $first_day_of_month); // Total days in the current month
            $start_day_of_week = date('w', $first_day_of_month);   // 0 (Sun) to 6 (Sat)

            // Print empty cells for days before the first of the month
            for ($i = 0; $i < $start_day_of_week; $i++) {
                echo '<div class="day-cell inactive"></div>';
            }

            // Print the days of the current month
            for ($day = 1; $day <= $total_days_of_month; $day++) {
                $class = 'day-cell';

                // Highlight the searched day or today's date
                if (isset($day_to_highlight) && $day == $day_to_highlight) {
                    $class .= ' highlighted';
                }

                echo '<div class="' . $class . '">' . $day . '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>

