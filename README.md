# site
Site Indignons-nous 

html + css, "old school".
Convention de codage : tabulations.

Pas de libs, pas de js (sauf pour le compte à rebours), pour l'instant.
Et pas de fontes Google, ni de ressources non libres !

Pour contribuer, faites un fork, puis des pull requests.

Structure du site :

2 fichiers seulement à la racine : 
 * index.html
 * favicon.ico (qui reste là pour les requêtes directes sur les images, par exemple)

et 2 dossiers :
 * global : la partie du site mise à jour automatiquement à partir d'ici.
 * local : toutes les personnalisations. 

À titre d'exemple, et parce que ça pourra changer sur les sites thématiques ou locaux,
et/ou être géré par un CMS ou un script idoine, la page visuels se trouve dans le dossier /local.

La personnalisation se fait avec SSI (Server-Side Include). Ça fonctionne sans rien installer de plus chez la plupart des hébergeurs.

La CSS /global/global.css a vocation a contenir tout ce qui doit être identique sur toutes les pages.
Tout ce qui se trouve dans /local PEUT charger une seconde CSS (ce n'est pas obligatoire !).

Carte interactive : https://umap.openstreetmap.fr/fr/map/indignons-nous_1261654

Charte graphique fournie initialement par les groupes Telegram :
![charte_1](https://github.com/user-attachments/assets/f6bc0a41-7891-4b10-a105-e0147a520b7d)

![impression](https://github.com/user-attachments/assets/bf0dcb77-6ed2-40b1-9326-1014108c9c1e)

