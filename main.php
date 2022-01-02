--|~|--|~|--|~|--|~|--|~|--|~|--

██  ████        ██████        ██
████    ██     ██           ████
██      ██   ████████     ██  ██
████████       ██       ██    ██
██             ██       █████████
██             ██             ██
██
 - codé en : UTF-8
 - langage : php
 - GitHub  : github.com/pf4-DEV
--|~|--|~|--|~|--|~|--|~|--|~|--

<?php

function debug_print($texte){
    global $DEBUG;
    if ($DEBUG) {
        echo $texte;
    }
}

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

function compar( $comparateur, $var1, $var2) {
    $a = getvar($var1);
    $b = getvar($var2);
    switch($comparateur) {
        case "==":
            return $a == $b;
        case "!=":
            return $a != $b;
        case ">":
            return $a > $b;
        case "<":
            return $a < $b;
        case ">=":
            return $a >= $b;
        case "<=":
            return $a <= $b;
        default:
            echo "Erreur de comparaison : " . $comparateur . "\n";
    }
}

function setvar($name, $valeur, $ACTIVE) {
    global $VARVAL, $VARNAME;
    for($i = 0; $i < count($VARNAME); $i++) {
        if($VARNAME[$i] == $name) {
            debug_print("$ACTIVE → modification: $name = $valeur\n");
            $VARVAL[$i] = $valeur;
            return;
        }
    }
    
    debug_print("$ACTIVE → ajout: $name = $valeur\n");
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
    global $VARVAL, $VARNAME, $DEBUG;
    $DEBUG = false;
    $VARNAME = array();
    $VARVAL = array();

    $code = str_replace("\r", "", $code);
    $code = explode("\n", $code);

    codeinloop($code, "main" ,1);
}

function bcl_ctrl($code, $i, $nom, $nb){
    $codetoloop = array();
                    
    for ($j = $i+1; $j < sizeof($code); $j++) {
        array_push($codetoloop, $code[$j]);
    }
    
    codeinloop($codetoloop, $nom, $nb);
    return $nom;
}

function codeinloop($code, $nom ,$max) {
    global $DEBUG;
    debug_print("demarrage de la boucle '$nom'\n");
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
                    $sauter = bcl_ctrl($code, $i, $args[1], $args[2]);
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

                else if ($mode == "Z") {
                    break;
                }

                else if ($mode == "B") {
                    setvar($args[1], compar($args[3], $args[2], $args[4]), $nom);
                }
                
                else if ($mode == "D") {
                    if ($args[1] == "on") {
                        $DEBUG = true;
                    }
                    else if ($args[1] == "off") {
                        $DEBUG = false;
                    }
                    else{
                        debugprint();
                    }
                }

                else if ($mode == "X") {
                    if (getvar($args[2]) == true) {
                        $sauter = bcl_ctrl($code, $i, $args[1], 1);
                    }
                    else {
                        $sauter =  $args[1];
                        debug_print("condition non remplie: $sauter\n");
                    }
                }

                else if ($mode == "S") {
                    echo $args[1] ."\n";
                }

                else if ($mode == "A") {
                    echo getvar($args[1]);
                }

                else {
                    echo "Erreur: $mode\n";
                }
            }
            else {
                debug_print("$nom → sauter: $ligne\n");
            }
        }
    }
}


start("
D off
V a 0
V 2 2
V 1 1
L find 100
    C a a + 1
    C mod a % 2
    B pair mod == 0
    B impair mod != 0
    X Pprinter pair
        A a
        S _est_pair
        E Pprinter
    X Iprinter impair
        A a
        S _est_impair
        E Iprinter
    E find
");?>