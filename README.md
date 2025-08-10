# site
Site Indignons-nous 

html + css, "old school".

Pas de libs, pas de js, pour l'instant.
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

Maquette Figma (indicative, car nécessité d'aller vite sur le contenu, quitte à améliorer le contenant ultérieurement): https://www.figma.com/design/Brb5gbV7ExTRWbjLLbg6kR/Site-Indignons-nous?node-id=1-2&t=EpvLpPLxupvC2Z16-0

<img width="1440" height="5820" alt="Desktop - 2" src="https://github.com/user-attachments/assets/1fb8dcf7-f862-4ad3-9133-9a4dc880427a" />


Charte graphique fournie initialement par les groupes Telegram :
![charte_1](https://github.com/user-attachments/assets/f6bc0a41-7891-4b10-a105-e0147a520b7d)

![impression](https://github.com/user-attachments/assets/bf0dcb77-6ed2-40b1-9326-1014108c9c1e)

