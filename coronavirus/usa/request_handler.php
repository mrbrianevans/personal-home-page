<?php
$state_list = array('AL'=>"Alabama",
    'AK'=>"Alaska",
    'AZ'=>"Arizona",
    'AR'=>"Arkansas",
    'CA'=>"California",
    'CO'=>"Colorado",
    'CT'=>"Connecticut",
    'DE'=>"Delaware",
    'FL'=>"Florida",
    'GA'=>"Georgia",
    'HI'=>"Hawaii",
    'ID'=>"Idaho",
    'IL'=>"Illinois",
    'IN'=>"Indiana",
    'IA'=>"Iowa",
    'KS'=>"Kansas",
    'KY'=>"Kentucky",
    'LA'=>"Louisiana",
    'ME'=>"Maine",
    'MD'=>"Maryland",
    'MA'=>"Massachusetts",
    'MI'=>"Michigan",
    'MN'=>"Minnesota",
    'MS'=>"Mississippi",
    'MO'=>"Missouri",
    'MT'=>"Montana",
    'NE'=>"Nebraska",
    'NV'=>"Nevada",
    'NH'=>"New Hampshire",
    'NJ'=>"New Jersey",
    'NM'=>"New Mexico",
    'NY'=>"New York",
    'NC'=>"North Carolina",
    'ND'=>"North Dakota",
    'OH'=>"Ohio",
    'OK'=>"Oklahoma",
    'OR'=>"Oregon",
    'PA'=>"Pennsylvania",
    'RI'=>"Rhode Island",
    'SC'=>"South Carolina",
    'SD'=>"South Dakota",
    'TN'=>"Tennessee",
    'TX'=>"Texas",
    'UT'=>"Utah",
    'VT'=>"Vermont",
    'VA'=>"Virginia",
    'WA'=>"Washington",
    'WV'=>"West Virginia",
    'WI'=>"Wisconsin",
    'WY'=>"Wyoming");
foreach ($state_list as $code=>$fullname){
    $state_list[$code] = strtolower($state_list[$code]);
}
$state_list = array_flip($state_list);
foreach ($state_list as $full_name=>$code){
    $state_list[$full_name] = "US-" . $code;
}
if(isset($_GET["weekly"])){
    require "../covidModel.php";
    $covidModel = new covidModel();
    echo $covidModel->getWeeklyDeathsByState();
}
elseif (isset($_GET['usa-map'])) {
    $state_list_curl_request = curl_init("https://corona.lmao.ninja/v2/historical/usacounties");
    curl_setopt($state_list_curl_request, CURLOPT_RETURNTRANSFER, 1);
    $states = curl_exec($state_list_curl_request);
    curl_close($state_list_curl_request);

    $states = (json_decode($states));
    shuffle($states); //randomise
    $state_data = [];
    $state_totals = [];
    $state_county_counts = [];
    $state_average = [];
    $fourteen_days_ago = date("n/j/y", time()-(14*86400));
    $today = date("n/j/y", time()-(86400));
    $count_limit = 0;
    $start_time = microtime(true);
    foreach ($states as $state){
        if($count_limit++ > 500) break; //use this to limit requests for testing purposes
        if(!isset($state_list[$state])) continue; // dont waste time if its not on the map
        $state_name = ucfirst($state);
        //cURL request
        $state_curl_request = curl_init("https://corona.lmao.ninja/v2/historical/usacounties/$state?lastdays=14");
        curl_setopt($state_curl_request, CURLOPT_RETURNTRANSFER, 1);
        $state_data[$state] = json_decode(curl_exec($state_curl_request));
        curl_close($state_curl_request);

        foreach($state_data[$state] as $county){
            $timeline = $county->timeline;
            $cases_history = $timeline->cases;
            $county_name = ucfirst($county->county);
            $previous = $cases_history->$fourteen_days_ago;
            $last_day_of_decline = null;
            foreach ($cases_history as $date=>$count){
                if ($count > $previous){
                    $last_day_of_decline = null;
                }else{
                    if(is_null($last_day_of_decline))
                        $last_day_of_decline = $date;
                }
                $previous = $count;
            }
            unset($date);
            unset($count);
            if(isset($last_day_of_decline)){
                $days_of_decline = floor((time() - strtotime($last_day_of_decline)) / 86400);
            }else{
                $days_of_decline = 0;
            }
            if($cases_history->$today > 0){
                $state_county_counts[$state] += 1;
                $state_totals[$state] += $days_of_decline;
            }
        }
        unset($county);
        unset($days_of_decline);
        $time_taken = microtime(true) - $start_time;
        $start_time = microtime(true);
    }
    unset($state);
    foreach ($states as $state){
        if(isset($state_list[$state])){
            $average = $state_totals[$state]/$state_county_counts[$state];
            $average = round($average, 1);
            $state_average[ucwords($state)] = $average;
        }
    }
    $state_average['max'] = 14;
    $state_average['min'] = 0;
    echo json_encode($state_average);
}
elseif (isset($_GET['usa-map-daily'])) {
    $states = file_get_contents("https://corona.lmao.ninja/v2/historical/usacounties");
    $states = json_decode($states);
    $state_data = [];
    $state_totals = [];
    $state_county_counts = [];
    $state_average = [];
    $fourteen_days_ago = date("n/j/y", time()-(14*86400));
    $today = date("n/j/y", time()-(86400));

    foreach ($states as $state){
        $state_name = ucfirst($state);
        $state_data[$state] = json_decode(file_get_contents("https://corona.lmao.ninja/v2/historical/usacounties/$state?lastdays=14"));
        foreach($state_data[$state] as $county){
            $timeline = $county->timeline;
            $cases_history = $timeline->cases;
            $dates = array_keys(json_decode(json_encode($cases_history), true));

            $one_day_ago = end($dates);
            $two_days_ago = prev($dates);
            $previous_daily_increase = ($cases_history->$one_day_ago)-($cases_history->$two_days_ago);
            $days_of_new_case_decline = 0;
            for ($index = count($dates)-2; $index >= 0; $index--){
                $date = $dates[$index];
                $previous_date = $dates[$index-1];
                $cases_on_date = $cases_history->$date;
                $cases_on_previous_date = $cases_history->$previous_date;
                $daily_increase = max($cases_on_date-$cases_on_previous_date, 0);
                if($daily_increase >= $previous_daily_increase){
                    $days_of_new_case_decline++;
                    $previous_daily_increase = $daily_increase;
                } else{
                    break;
                }
            }
            unset($date);
            unset($index);

            if(end($cases_history) > 0){
                $state_county_counts[$state] += 1;
                $state_totals[$state] += $days_of_new_case_decline;
            }
        }
        unset($county);
        unset($days_of_decline);
    }
    unset($state);
    foreach ($states as $state){
        if(isset($state_list[$state])){
            $average = $state_totals[$state]/$state_county_counts[$state];
            $average = round($average, 3);
            $state_average[ucwords($state)] = $average;
        }
    }
    $state_average['max'] = 14;
    $state_average['min'] = 0;
    echo json_encode($state_average);
}
elseif (isset($_GET['usa-map-deaths'])) {
    $statesData = file_get_contents("https://corona.lmao.ninja/v2/states");
    $statesData = json_decode($statesData);
    $state_deaths = [];
    foreach($statesData as $stateDatum){
        if(isset($state_list[strtolower($stateDatum->state)]))
            $state_deaths[$stateDatum->state] = $stateDatum->deathsPerOneMillion;
    }
    echo json_encode($state_deaths);
}
elseif (isset($_GET['usa-update-date'])) {
    $usa_data = file_get_contents("https://corona.lmao.ninja/v2/countries/usa");
    $usa_data = json_decode($usa_data);
    $usa_date = date("j M", substr($usa_data->updated, 0, 10));
    echo $usa_date;
}
elseif (isset($_GET['usa-stored-data'])) {
    echo json_encode(json_decode(file_get_contents("../state-case-history.txt")));
}
elseif (isset($_GET['usa-new-case-stored-data'])) {
    echo json_encode(json_decode(file_get_contents("https://brianevans.tech/coronavirus/state-new-case-history.txt")));
}
elseif (isset($_GET['test'])) {
    echo '{"Alabama":1.100000000000000088817841970012523233890533447265625,"Alaska":10.300000000000000710542735760100185871124267578125,"Arizona":3.399999999999999911182158029987476766109466552734375,"Arkansas":5.0999999999999996447286321199499070644378662109375,"California":3.5,"Colorado":4.5999999999999996447286321199499070644378662109375,"Connecticut":1.399999999999999911182158029987476766109466552734375,"Delaware":3.600000000000000088817841970012523233890533447265625,"District Of Columbia":9.300000000000000710542735760100185871124267578125,"Florida":1.6999999999999999555910790149937383830547332763671875,"Georgia":1.100000000000000088817841970012523233890533447265625,"Hawaii":3.70000000000000017763568394002504646778106689453125,"Idaho":7.9000000000000003552713678800500929355621337890625,"Illinois":3.70000000000000017763568394002504646778106689453125,"Indiana":1.5,"Iowa":6.29999999999999982236431605997495353221893310546875,"Kansas":8.300000000000000710542735760100185871124267578125,"Kentucky":4.0999999999999996447286321199499070644378662109375,"Louisiana":1.3000000000000000444089209850062616169452667236328125,"Maine":1.899999999999999911182158029987476766109466552734375,"Maryland":1.6999999999999999555910790149937383830547332763671875,"Massachusetts":2.5,"Michigan":2.29999999999999982236431605997495353221893310546875,"Minnesota":5.9000000000000003552713678800500929355621337890625,"Mississippi":1.1999999999999999555910790149937383830547332763671875,"Missouri":7,"Montana":11,"Nebraska":8.0999999999999996447286321199499070644378662109375,"Nevada":6.4000000000000003552713678800500929355621337890625,"New Hampshire":2.29999999999999982236431605997495353221893310546875,"New Jersey":0.6999999999999999555910790149937383830547332763671875,"New Mexico":6.0999999999999996447286321199499070644378662109375,"New York":2.29999999999999982236431605997495353221893310546875,"North Carolina":3.20000000000000017763568394002504646778106689453125,"North Dakota":10.4000000000000003552713678800500929355621337890625,"Ohio":2.100000000000000088817841970012523233890533447265625,"Oklahoma":4.70000000000000017763568394002504646778106689453125,"Oregon":6.29999999999999982236431605997495353221893310546875,"Pennsylvania":2.100000000000000088817841970012523233890533447265625,"Rhode Island":2.399999999999999911182158029987476766109466552734375,"South Carolina":1.399999999999999911182158029987476766109466552734375,"South Dakota":10.199999999999999289457264239899814128875732421875,"Tennessee":2.899999999999999911182158029987476766109466552734375,"Texas":6,"Utah":6.5999999999999996447286321199499070644378662109375,"Vermont":2.79999999999999982236431605997495353221893310546875,"Virginia":3.600000000000000088817841970012523233890533447265625,"Washington":3.20000000000000017763568394002504646778106689453125,"West Virginia":5.79999999999999982236431605997495353221893310546875,"Wisconsin":5.5,"Wyoming":7.4000000000000003552713678800500929355621337890625,"max":14,"min":0}';
}
