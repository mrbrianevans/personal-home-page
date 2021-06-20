<?php

if (isset($_GET['daily-cases-improvement'])) {
    $states = file_get_contents("https://corona.lmao.ninja/v2/historical/usacounties");
    $states = json_decode($states);
    $state_data = [];
    $state_totals = [];
    $state_county_counts = [];
    $state_average = [];
    $fourteen_days_ago = date("n/j/y", time() - (14 * 86400));
    $today = date("n/j/y", time() - (86400));

    foreach ($states as $state) {
        $state_name = ucfirst($state);
        $state_data[$state] = json_decode(file_get_contents("https://corona.lmao.ninja/v2/historical/usacounties/$state?lastdays=14"));
        foreach ($state_data[$state] as $county) {
            $timeline = $county->timeline;
            $cases_history = $timeline->cases;
            $dates = array_keys(json_decode(json_encode($cases_history), true));

            $one_day_ago = end($dates);
            $two_days_ago = prev($dates);
            $previous_daily_increase = ($cases_history->$one_day_ago) - ($cases_history->$two_days_ago);
            $days_of_new_case_decline = 0;
            for ($index = count($dates) - 2; $index >= 0; $index--) {
                $date = $dates[$index];
                $previous_date = $dates[$index - 1];
                $cases_on_date = $cases_history->$date;
                $cases_on_previous_date = $cases_history->$previous_date;
                $daily_increase = max($cases_on_date - $cases_on_previous_date, 0);
                if ($daily_increase >= $previous_daily_increase) {
                    $days_of_new_case_decline++;
                    $previous_daily_increase = $daily_increase;
                } else {
                    break;
                }
            }
            unset($date);
            unset($index);

            if (end($cases_history) > 0) {
                $state_county_counts[$state] += 1;
                $state_totals[$state] += $days_of_new_case_decline;
            }
        }
        unset($county);
        unset($days_of_decline);
    }
    unset($state);
    foreach ($states as $state) {
        if (isset($state_list[$state])) {
            $average = $state_totals[$state] / $state_county_counts[$state];
            $average = round($average, 3);
            $state_average[ucwords($state)] = $average;
        }
    }
    $state_average['max'] = 14;
    $state_average['min'] = 0;
    echo json_encode($state_average);
}