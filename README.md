![](https://raw.githubusercontent.com/pf4-DEV/kea-project/main/doc/kea.png)

## lancer un programme

```shell
php main.php <nom du fichier>
```

## mode et args du kea brut

| description             | mod | arg1          | arg2        | agr3        | arg4 |
|-------------------------|-----|---------------|-------------|-------------|------|
| afficher une variable   | A   | var           |             |             |      |
| comparaison logique     | B   | var de sortie | var1        | comparateur | var2 |
| calcul                  | C   | var de sortie | var1        | opération   | var2 |
| debug                   | D   | on/off/print  |             |             |      |
| sortie de boucle        | E   | nom de boucle |             |             |      |
| input                   | I   | var           |             |             |      |
| boucle a tours          | L   | nom de boucle | nb de tours |             |      |
| afficher du texte       | S   | texte         |             |             |      |
| assignation de variable | V   | var           | valleur     |             |      |
| execution si condition  | X   | nom de boucle | var (B)     |             |      |
| break                   | Z   |               |             |             |      |