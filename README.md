## mode et args du kea brut

| description             | mod | arg1          | arg2        | agr3        | arg4 |
|-------------------------|-----|---------------|-------------|-------------|------|
| affichier une variable  | A   | var           |             |             |      |
| comparaison logique     | B   | var de sortie | var1        | comparateur | var2 |
| calcul                  | C   | var de sortie | var1        | opération   | var2 |
| debug                   | D   | on/off/print  |             |             |      |
| sortie de boucle        | E   | nom de boucle |             |             |      |
| boucle a tours          | L   | nom de boucle | nb de tours |             |      |
| affichier du texte      | S   | texte         |             |             |      |
| assignation de variable | V   | var           | valleur     |             |      |
| execution si condition  | X   | nom de boucle | var (B)     |             |      |
| break                   | Z   |               |             |             |      |

## trouver les nombres premiers
```
S runing
V i 1
V 0 0
V 0.5 0.5
V 1 1
V 2 2
V to 1000000
L nbr to
    C i i + 1
    C mod i % 2
    B inpair mod != 0
    X done inpair
        B good 1 == 1
        C max i ^ 0.5
        C max max - 1
        V x 1
        L all max
            C x x + 1
            C mod i % x
            B bad mod == 0
            X no bad
                B good 0 == 1
                Z
                E no
            E all
        X prem good
            A i
            S
            E prem
        E done
    E nbr
```