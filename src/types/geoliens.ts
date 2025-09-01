export type Lien = {
    type: string | null;
    nom: string | null;
    url: string | null;
    territoire: string | null;
    latitude: string | null;
    longitude: string | null;
};

export type Lieu = {
    nom: string | null;
    liens: Lien[];
    sousLieux: Lieu[];
};

export type Links = {
    reseaux: Lien[];
    regions: Lieu[];
    monde: Lieu[];
    autres: Lieu[];
};