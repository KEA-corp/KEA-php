<h1 align = center> KEA 1.1 </h1>

<p align="center">
    <img src="https://raw.githubusercontent.com/pf4-DEV/kea-project/main/doc/kea.png">
</p>

## FAQ

- **Qu'est-ce que le KEA ?**\
Et bien le KEA est langage de programmation interprété bassé sur les boucles...
- **Les boucles ?**\
Oui, oui, En KEA tout passe par des boucles: les condition sont des boucles, les fonction aussi...
- **Pourquoi *'KEA'* ?**\
Au depart, l'interpréteur s'appellait *'inter'* pour interpréteur puis *'Pinter'* pour PHP-interpréteur se fait largement penser a *'panther'* alors pourquoi un nom d'animal, le kea et rapide, atypique, et le nom n'est pas trop long...
- **Et l'interpréteur il est en C ?**\
Non, il est en PHP le C était trop bas niveau, et le PHP est plus haut niveau, plus facile à apprendre, et plus facile à utiliser sans être trop lent.
- **Pour les performances sa donne quoi?**\
~~feur~~ Les calculs sont faits en PHP, c'est donc relativement rapide, mais il reste en langage interprété, par un langage interprété, il faut pas s'attendre à des miracles...

## lancer un programme kea

```shell
php main.php <nom du fichier>
```

## mode et args du kea brut

| description                | mod | arg1               | arg2        | agr3        | arg4 |
|----------------------------|-----|--------------------|-------------|-------------|------|
| afficher une variable      | A   | var                |             |             |      |
| comparaison logique        | B   | var de sortie      | var1        | comparateur | var2 |
| calcul                     | C   | var de sortie      | var1        | opération   | var2 |
| debug                      | D   | on/off/print       |             |             |      |
| sortie de boucle           | E   | nom de boucle      |             |             |      |
| déclaration de fonction    | F   | nom de boucle      |             |             |      |
| copier une variable        | H   | var de sortie      | var modelle |             |      |
| input                      | I   | var                |             |             |      |
| boucle a tours             | L   | nom de boucle      | nb de tours |             |      |
| afficher du texte          | S   | texte              |             |             |      |
| téléphoner a une fonction  | T   | nom de la fonction |             |             |      |
| assignation de variable    | V   | var                | valleur     |             |      |
| execution si condition     | X   | nom de boucle      | var (B)     |             |      |
| break                      | Z   |                    |             |             |      |