export type Lien = {
    type: string;
    nom: string;
    url: string;
    territoire: string;
    latitude: string;
    longitude: string;
};

export type Ressources = {
    type: string;
    icon: string;
    liens: Lien[];
};

export type Lieu = {
    nom: string;
    ressources: Ressources[];
    sousLieux: Lieu[];
};