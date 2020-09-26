<?php
$players = 3;
$dice = 4;
if($_POST["players"] != NULL){
    $players = $_POST["players"];
}

if($_POST["dices"] != NULL){
    $dice = $_POST["dices"];
}

$currentPlayers = array();
$round = 1;

function play($players, $dice){
    global $players_arrs, $currentPlayers, $diceNumbers, $round;
    for ($x = 1; $x <= $players; $x++) {
        $currentPlayers[$x] = $x;
        $players_arrs[$x]['dices'] = array();
        $players_arrs[$x]['point'] = 0;
        $players_arrs[$x]['player'] = $x;
        $players_arrs[$x]['dice_owned'] = $dice;
        for ($y = 0; $y < $dice; $y++) {
            $players_arrs[$x]['dices'][$y] = rand(1,6);
        }
    }
    print_r("\n==========ROUND $round Lempar Dadu=========\n");
    foreach ($players_arrs as $players_arr) {
        $result = "Pemain #".$players_arr['player']."(".$players_arr['point'].") :".implode(",", $players_arr['dices']) ;
        if($players_arr['dice_owned'] == 0){
            $result = $result." (Berhenti bermain karena tidak memiliki dadu)";
        }
        echo "\n".$result."\n";
    }
    $round++;
    evaluate(($players_arrs));
}

function evaluate($players_arrs){
    global $players_arrs, $currentPlayers;
    $player_seq = 1;
    foreach ($players_arrs as $players_arr) {
        foreach ($players_arr['dices'] as $key => $dice) {
            $player = $players_arr['player'];
            if($dice == 6){
                $players_arrs[$player]['point'] += 1;
                $players_arrs[$player]['dice_owned'] -= 1;
                unset($players_arrs[$player]['dices'][$key]);
                
            }

            if($dice == 1){
                $temp_player_seq = $player_seq;
                $temp_player_seq += 1;
                $players_arrs[$player]['dice_owned'] -= 1;
                if(array_key_exists($temp_player_seq, $players_arrs)){
                    $players_arrs[$temp_player_seq]['dice_owned'] += 1;
                    $players_arrs[$temp_player_seq]['dices'][] = 1;
                }else{
                    $players_arrs[array_key_first($players_arrs)]['dice_owned'] += 1;
                    $players_arrs[array_key_first($players_arrs)]['dices'][] = 1;
                    
                }
                unset($players_arrs[$player]['dices'][$key]);
                
            }

        }
        
        $player_seq++;
        
    }
    
    print_r("\n==========EVALUATE=========\n");
    $lastPlayer = '';
    foreach ($players_arrs as $players_arr) {
        $result = "Pemain #".$players_arr['player']."(".$players_arr['point'].") :".implode(",", $players_arr['dices']) ;
        if($players_arr['dice_owned'] == 0){
            $result = $result." (Berhenti bermain karena tidak memiliki dadu)";
            if(array_key_exists($players_arr['player'], $currentPlayers)){
                unset($currentPlayers[$players_arr['player']]);
                $lastPlayer = reset($currentPlayers);
            }
        }
        echo "\n".$result."\n";
    }
    if(count($currentPlayers) > 1){
        nextRound($players_arrs);
    }else{
        $result = "Game berakhir karena hanya pemain #$lastPlayer yang memiliki dadu.\n";
        echo $result;
    }
    
}


function nextRound($player_arrs){
    global $players_arrs, $diceNumbers, $round, $currentPlayers;
    foreach ($players_arrs as $players_arr) {
        $player = $players_arr['player'];
        if ($players_arr['dice_owned'] != 0){
            unset($players_arrs[$player]['dices']);
            $players_arrs[$player]['dices'] = array();
            for ($y = 0; $y < $players_arr['dice_owned']; $y++) {
                $players_arrs[$player]['dices'][$y] = rand(1,6);
                
            }
        }
    }
    print_r("\n==========ROUND $round Lempar Dadu=========\n");
    foreach ($players_arrs as $players_arr) {
        $result = "Pemain #".$players_arr['player']."(".$players_arr['point'].") :".implode(",", $players_arr['dices']) ;
        if($players_arr['dice_owned'] == 0){
            $result = $result." (Berhenti bermain karena tidak memiliki dadu)";
        }
        echo "\n".$result."\n";
    }
    $round++;
    evaluate(($players_arrs));
}



play($players, $dice);

?>