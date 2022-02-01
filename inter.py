from ast import arg


version = "1.1.49"

compar = lambda comparateur, var1, var2 : eval(f"var1 {comparateur} var2", locals())
calc = lambda calcul, var1, var2 : eval(f"var1 {calcul} var2", locals())

def empty(liste, index):
    try:
        return liste[index]
    except:
        return False

def isset(variable):
    return True if variable in locals() or variable in globals() else False

def setsauter(valeur, nom):
    debug_print(f"{nom} → sauter = '{valeur}'\n")
    return valeur

def getvar(name):
    global VAR
    if empty(VAR, name):
        return VAR[name]
    else:
        print(f"variable {name} non trouvée")
        return ""

def debug_print_all():
    print("Non implémenté")

def user_input(var, ACTIVE):
    setvar(var, input(), ACTIVE)

def setvar(name, valeur, ACTIVE):
    global VAR
    debug_print(f"{ACTIVE} → V '{name}' = '{valeur}'\n")
    VAR[name] = valeur

def debug_print(texte, blue=False):
    if DEBUG:
        if blue:
            print("\033[94m" + texte + "\033[0m", end = "")
        else:
            print(texte, end = "")

def start(code):
    global VAR, DEBUG, FUNCTIONS
    DEBUG = False
    VAR = {}
    FUNCTIONS = {}

    code = code.replace(";", "\n")
    code = code.replace("\r", "")

    code = code.split("\n")

    codeinloop(code, "main" ,1)

def save_fonction(name, code, i):
    global FUNCTIONS
    FUNCTIONS[name] = [code, i]

def bcl_ctrl(code, i, nom, nb):
    codetoloop = []
    for j in range(i+1, len(code)):
        codetoloop.append(code[j])
    
    return codeinloop(codetoloop, nom, nb)

def codeinloop(code, nom ,max):
    global DEBUG, FUNCTIONS
    debug_print(f"DEMARAGE DE LA BOUCLE '{nom}'\n")
    sauter = setsauter("", nom)
    dobreak = 0
    for rep in range(max):
        for i in range(len(code)):
            ligne = code[i].strip()

            debug_print(f"[{nom}]({rep}~{i}) *** {ligne} ***\n", True)

            args = ligne.split(" ")
            mode = args[0]
            
            if sauter == "" or (mode == "E" and args[1] == sauter):
                if sauter != "":
                    sauter = setsauter("", nom)

                if mode == "":
                    continue

                elif mode == "V":
                    var = args[1]
                    try:
                        val = int(args[2])
                    except:
                        val = args[2]
                    setvar(var, val, nom)

                elif mode == "L":
                    dobreak = bcl_ctrl(code, i, args[1], getvar(args[2]))
                    sauter = setsauter(args[1], nom)

                elif mode == "E":
                    if args[1] == nom:
                        debug_print("ARRET DE LA BOUCLE 'nom'\n")
                        break

                elif mode == "C":
                    result = calc(args[3], getvar(args[2]), getvar(args[4]))
                    setvar(args[1], result, nom)

                elif mode == "Z":
                    dobreak = 1
                    if (isset(args[1])):
                        dobreak = getvar(args[1])

                elif mode == "B":
                    setvar(args[1], compar(args[3], args[2], args[4]), nom)

                elif mode == "H":
                    setvar(args[1], getvar(args[2]), nom)

                elif mode == "F":
                    save_fonction(args[1], code, i)
                    sauter = setsauter(args[1], nom)

                elif mode == "T":
                    fonction = args[1]
                    if empty(FUNCTIONS, fonction):
                        fonc_code = FUNCTIONS[fonction][0]
                        oldi = FUNCTIONS[fonction][1]
                        bcl_ctrl(fonc_code, oldi, args[1], 1)
                    
                    else:
                        print("Fonction fonction non trouvée")

                elif mode == "D":
                    if args[1] == "on":
                        DEBUG = True
                    
                    elif args[1] == "off":
                        DEBUG = False

                    else:
                        debug_print_all()

                elif mode == "R":
                    rand = rand(0, getvar(args[2]))
                    setvar(args[1], rand, nom)
                

                elif mode == "X":
                    if getvar(args[2]) == True:
                        dobreak = bcl_ctrl(code, i, args[1], 1)
                        sauter = setsauter(args[1], nom)

                    else:
                        sauter = setsauter(args[1], nom)
                        debug_print("condition non remplie: sauter\n")
                    
                

                elif mode == "S":
                    if empty(args, 1):
                        print("\n")
                    
                    else:
                        print("\033[93m" + args[1].replace("_", " ", ) + "\033[0m", end = "")
                    
                    if DEBUG:
                        print("\n")

                elif mode == "I":
                    user_input(args[1], nom)

                elif mode == "A":
                    print("\033[93m" + str(getvar(args[1])) + "\033[0m", end = "")
                    if DEBUG:
                        print("\n")

                elif mode != "//":
                    print("Erreur de mode: mode\n")

            else:
                debug_print("nom → passer 'ligne'\n")
            
            if dobreak > 0:
                return dobreak - 1

