<?php
/*
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
*/

function debug_print($texte){
    global $DEBUG;
    if ($DEBUG) {
        echo $texte;
    }
}

function getvar($name) {
    global $VAR;
    if (isset($VAR[$name])) {
        return $VAR[$name];
    } else {
        echo "Variable $name non trouvée\n";
        return "";
    }
}

function user_input($var, $ACTIVE) {
    setvar($var, readline(), $ACTIVE);
}

function debugprint() {
    echo "\nVARIABLES:\n";
    global $VAR;
    foreach ($VAR as $key => $value) {
        echo "$key = $value\n";
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
    global $VAR;
    debug_print("$ACTIVE → variable: $name = $valeur\n");
    $VAR[$name] = $valeur;
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
    global $VAR, $DEBUG;
    $DEBUG = false;
    $VAR = [];

    $code = str_replace(";", "\n", $code);
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
            if ($DEBUG){
                $debut = microtime(true);
            }
            
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
                    $sauter = bcl_ctrl($code, $i, $args[1], getvar($args[2]));
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
                    if (empty($args[1])) {
                        echo "\n";
                    }
                    else {
                        echo $args[1];
                    }
                }

                else if ($mode == "I") {
                    user_input($args[1], $nom);
                }

                else if ($mode == "A") {
                    echo getvar($args[1]);
                }

                else if ($mode =! "//") {
                    echo "Erreur de mode: $mode\n";
                }
            }
            else {
                debug_print("$nom → sauter: $ligne\n");
            }
            if ($DEBUG) {
                $fin = microtime(true);
                $duree = $fin - $debut;
                debug_print("$nom → $ligne ($duree s)\n");
            }
        }
    }
}

if (isset($argv[1])) {
    $code = file_get_contents($argv[1]);
    start($code);
}
else {
    echo "Erreur: pas de fichier\n";
}