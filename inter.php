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

$version = "1.1.39";

function debug_print($texte, $blue = false){
    global $DEBUG;
    if ($DEBUG) {
        if ($blue) {
            echo "\e[0;1;30m$texte\e[0m";
        } else {
            echo $texte;
        }
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

function debug_print_all() {
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
        case "=":
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
            echo "Erreur de comparaison : '$comparateur' \n";
    }
}

function setvar($name, $valeur, $ACTIVE) {
    global $VAR;
    debug_print("$ACTIVE → V '$name' = '$valeur'\n");
    $VAR[$name] = $valeur;
}

function setsauter($valeur, $nom) {
    debug_print("$nom → sauter = '$valeur'\n");
    return $valeur;
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
    global $VAR, $DEBUG, $FUNCTIONS;
    $DEBUG = false;
    $VAR = [];
    $FUNCTIONS = [];

    $code = str_replace(";", "\n", $code);
    $code = str_replace("\r", "", $code);
    $code = explode("\n", $code);

    codeinloop($code, "main" ,1);
}

function save_fonction($name, $code, $i) {
    global $FUNCTIONS;
    $FUNCTIONS[$name] = [$code, $i];
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
    global $DEBUG, $FUNCTIONS;
    debug_print("demarrage de la boucle '$nom'\n");
    $sauter = setsauter("", $nom);
    for ($rep = 0; $rep < $max; $rep++) {
        for ($i = 0; $i < sizeof($code); $i++) {
            $ligne = $code[$i];
            $ligne = trim($ligne);

            debug_print("[$nom]($i) *** $ligne ***\n", true);

            $args = explode(" ", $ligne);
            $mode = $args[0];
            
            if($sauter == "" || ($mode == "E" && $args[1] == $sauter)){
                if ($sauter != "") {
                    $sauter = setsauter("", $nom);
                }

                if($mode == "") {
                    continue;
                }

                else if ($mode == "V"){
                    $var = $args[1];
                    $val = $args[2];
                    setvar($var, $val, $nom);
                }

                else if ($mode == "L") {
                    $sauter = setsauter(bcl_ctrl($code, $i, $args[1], getvar($args[2])), $nom);
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

                else if ($mode == "H") {
                    setvar($args[1], getvar($args[2]), $nom);
                }

                else if ($mode == "F"){
                    echo "$i, $nom, $args[1]\n";
                    save_fonction($args[1], $code, $i);
                    $sauter = setsauter($args[1], $nom);
                }

                else if ($mode == "T"){
                    $fonction = $args[1];
                    if (isset($FUNCTIONS[$fonction])) {
                        $code = $FUNCTIONS[$fonction][0];
                        $oldi = $FUNCTIONS[$fonction][1];
                        bcl_ctrl($code, $oldi, $args[1], 1);
                    }
                    else {
                        echo "Fonction $fonction non trouvée\n";
                    }
                }
                
                else if ($mode == "D") {
                    if ($args[1] == "on") {
                        $DEBUG = true;
                    }
                    else if ($args[1] == "off") {
                        $DEBUG = false;
                    }
                    else{
                        debug_print_all();
                    }
                }

                else if ($mode == "X") {
                    if (getvar($args[2]) == true) {
                        $sauter = setsauter(bcl_ctrl($code, $i, $args[1], 1), $nom);
                    }
                    else {
                        $sauter = setsauter($args[1], $nom);
                        debug_print("condition non remplie: $sauter\n");
                    }
                }

                else if ($mode == "S") {
                    if (empty($args[1])) {
                        echo "\n";
                    }
                    else {
                        echo str_replace("_", " ", $args[1]);
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
                debug_print("$nom → passer '$ligne'\n");
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