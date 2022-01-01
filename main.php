<?php

function getvar($name) {
    global $VARVAL, $VARNAME;
    for($i = 0; $i < count($VARNAME); $i++) {
        if($VARNAME[$i] == $name) {
            return $VARVAL[$i];
        }
    }
}

function debugprint() {
    echo "\nVARIABLES:\n";
    global $VARVAL, $VARNAME;
    for($i = 0; $i < count($VARNAME); $i++) {
        echo "[§" . $i . "] " . $VARNAME[$i] . " = " . $VARVAL[$i] . "\n";
    }
}

function setvar($name, $valeur, $ACTIVE) {
    global $VARVAL, $VARNAME;
    for($i = 0; $i < count($VARNAME); $i++) {
        if($VARNAME[$i] == $name) {
            echo "$ACTIVE → modification: $name = $valeur\n";
            $VARVAL[$i] = $valeur;
            return;
        }
    }
    
    echo "$ACTIVE → ajout: $name = $valeur\n";
    array_push($VARNAME, $name);
    array_push($VARVAL, $valeur);
}

function calc($calcul, $var1, $var2) {
    if ($calcul == "+"){
        return $var1 + $var2;
    }
    else if ($calcul == "-"){
        return $var1 - $var2;
    }
    else if ($calcul == "*"){
        return $var1 * $var2;
    }
    else if ($calcul == "/"){
        return $var1 / $var2;
    }
    else if ($calcul == "^"){
        return pow($var1, $var2);
    }
    else if ($calcul == "%"){
        return $var1 % $var2;
    }
    else {
        echo "calc: operateur inconnu: $calcul\n";
    }
}

function start ($code) {
    global $VARVAL, $VARNAME;
    $VARNAME = array();
    $VARVAL = array();

    $code = str_replace("\r", "", $code);
    $code = explode("\n", $code);

    codeinloop($code, "main" ,1);
}

function codeinloop($code, $nom ,$max) {
    echo "demarrage de la boucle '$nom'\n";
    $sauter = "";
    for ($rep = 0; $rep < $max; $rep++) {
        for ($i = 0; $i < sizeof($code); $i++) {
            $ligne = $code[$i];
            $ligne = trim($ligne);
            $args = explode(" ", $ligne);
            $mode = $args[0];
            
            if($sauter == "" || ($mode == "E" && $args[1] == $sauter)){

                $sauter = "";

                if($mode == "") {
                    continue;
                }

                else if ($mode == "V"){
                    $var = $args[1];
                    $val = $args[2];
                    setvar($var, $val, $nom);
                }

                else if ($mode == "L") {
                    $codetoloop = array();
                    
                    for ($j = $i+1; $j < sizeof($code); $j++) {
                        array_push($codetoloop, $code[$j]);
                    }
                    
                    $sauter = $args[1];
                    codeinloop($codetoloop, $args[1], $args[2]);
                }

                else if ($mode == "E") {
                    if ($args[1] == $nom) {
                        break;
                    }
                }

                else if ($mode == "C"){
                    $result = calc($args[3], getvar($args[2]), getvar($args[4]));
                    setvar($args[1], $result, $nom);
                }

                else if ($mode == "B") {
                    break;
                }               

                else {
                    echo "Erreur: $mode\n";
                }
            }
            else {
                echo "$nom → Sauter: $ligne\n";
            }
        }
    }
}


start("
V a 1
V b 2

C c a + b

");?>