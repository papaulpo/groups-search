export type Lien = {
    type: string;
    nom: string;
    url: string;
    territoire: string;
    latitude: string;
    longitude: string;
};

export type LieuHierarchique = {
    nom: string;
    liens: Lien[];
    sousLieux: LieuHierarchique[];
};

export type GeolienEntry = Lien | LieuHierarchique;

export type GeolienData = {
    reseaux: GeolienEntry[];
    regions: LieuHierarchique[];
};

// Type pour les géoliens traités avec informations hiérarchiques
export type ProcessedGeolien = Lien & { 
    lieuNom?: string; 
    fullPath?: string; 
};

// Props pour les composants
export interface SearchFormProps {
    resultsCount: number;
}

export interface GeolienCardProps {
    geolien: ProcessedGeolien;
}

export interface GeolienGridProps {
    geoliens: ProcessedGeolien[];
}