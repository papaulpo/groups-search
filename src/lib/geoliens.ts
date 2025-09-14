import type { GeolienData, Lien, LieuHierarchique } from '../types/geoliens';

// Remote source for geoliens
const REMOTE_URL = 'https://10s25.ovh/cms/?page=geoliens.json&var_mode=calcul';

// In-memory cache to limit fetches during a dev session
let memoized: GeolienData | null = null;
let memoizedAt = 0;

// TTL: keep fairly long in dev to avoid many calls; shorter in prod
const DEV_TTL_MS = 30 * 60 * 1000; // 30 minutes
const PROD_TTL_MS = 6 * 60 * 60 * 1000; // 6 hours

// File cache under public so it can be inspected/served if needed
const CACHE_FILE = 'public/data/geoliens.cache.json';

type RemoteRessource = {
  type: string;
  icon?: string;
  liens?: Array<any>;
};

type RemoteNode = {
  nom: string;
  ressources?: RemoteRessource[];
  sousLieux?: RemoteNode[];
};

// Utility: read JSON file if it exists
async function readJsonFile<T = any>(path: string): Promise<T | null> {
  const fs = await import('node:fs/promises');
  const fssync = await import('node:fs');
  const p = await import('node:path');
  const full = p.join(process.cwd(), path);
  try {
    if (!fssync.existsSync(full)) return null;
    const raw = await fs.readFile(full, 'utf-8');
    return JSON.parse(raw) as T;
  } catch {
    return null;
  }
}

// Utility: write JSON file (best-effort)
async function writeJsonFile(path: string, data: any): Promise<void> {
  try {
    const fs = await import('node:fs/promises');
    const p = await import('node:path');
    const dir = p.dirname(p.join(process.cwd(), path));
    await fs.mkdir(dir, { recursive: true });
    await fs.writeFile(p.join(process.cwd(), path), JSON.stringify(data, null, 2), 'utf-8');
  } catch {
    // ignore cache write errors
  }
}

// Compute a display-friendly name for a link if absent
function fallbackNomFromUrl(url: string, type: string, place: string): string {
  try {
    const u = new URL(url);
    return `${type} • ${place} (${u.hostname})`;
  } catch {
    return `${type} • ${place}`;
  }
}

// Map depth to a territoire label
function territoireForDepth(depth: number): string {
  if (depth <= 0) return 'National';
  if (depth === 1) return 'Régional';
  if (depth === 2) return 'Départemental';
  return 'Local';
}

// Flatten ressource groups into Lien[]
function flattenRessourcesToLiens(ressources: RemoteRessource[] | undefined, placeName: string, depth: number): Lien[] {
  if (!ressources || !Array.isArray(ressources)) return [];
  const territoire = territoireForDepth(depth);
  const liens: Lien[] = [];

  for (const res of ressources) {
    const type = res?.type || 'Site web';
    const links = Array.isArray(res?.liens) ? res!.liens! : [];
    for (const l of links) {
      // l might be an object or a string URL
      const url: string = typeof l === 'string' ? l : (l?.url || l?.href || '');
      if (!url) continue;
      const nom: string = typeof l === 'string' ? fallbackNomFromUrl(url, type, placeName) : (l?.nom || l?.label || fallbackNomFromUrl(url, type, placeName));
      const latitude = (typeof l?.latitude === 'number' ? String(l.latitude) : (l?.latitude || '')).toString();
      const longitude = (typeof l?.longitude === 'number' ? String(l.longitude) : (l?.longitude || '')).toString();
      liens.push({ type, nom, url, territoire, latitude, longitude });
    }
  }

  return liens;
}

// Convert a remote node into LieuHierarchique recursively
function transformNode(node: RemoteNode, depth: number = 0): LieuHierarchique {
  const liens = flattenRessourcesToLiens(node.ressources, node.nom, depth);
  const children = Array.isArray(node.sousLieux) ? node.sousLieux : [];
  const sousLieux = children.map((c) => transformNode(c, depth + 1));
  return { nom: node.nom, liens, sousLieux };
}

// Transform the entire remote payload to the local GeolienData shape
function transformRemoteToGeolienData(remote: any): GeolienData {
  // Expecting an array of top-level nodes
  const arr: RemoteNode[] = Array.isArray(remote) ? remote : [];
  const regions: LieuHierarchique[] = arr.map((n) => transformNode(n, 0));
  return { reseaux: [], regions };
}

async function fetchRemote(): Promise<any> {
  const res = await fetch(REMOTE_URL, {
    // Avoid accidental browser cache interference when running in dev
    cache: 'no-store',
    headers: { 'Accept': 'application/json' }
  });
  if (!res.ok) throw new Error(`Failed to fetch geoliens: ${res.status}`);
  return res.json();
}

export async function loadGeolienData(): Promise<GeolienData> {
  const now = Date.now();
  const ttl = import.meta.env.DEV ? DEV_TTL_MS : PROD_TTL_MS;

  // In-memory cache first
  if (memoized && now - memoizedAt < ttl) {
    return memoized;
  }

  // Try network
  try {
    const remote = await fetchRemote();
    const data = transformRemoteToGeolienData(remote);
    memoized = data;
    memoizedAt = now;
    // Best-effort file cache for dev/offline
    await writeJsonFile(CACHE_FILE, data);
    return data;
  } catch (e) {
    // Fallback to file cache
    const cached = await readJsonFile<GeolienData>(CACHE_FILE);
    if (cached) {
      memoized = cached;
      memoizedAt = now;
      return cached;
    }
    // Final fallback to legacy local dump if present
    try {
      const legacy = (await import('../pages/_geolieu.json')).default as GeolienData;
      memoized = legacy;
      memoizedAt = now;
      return legacy;
    } catch {
      // If everything fails, return empty structure
      return { reseaux: [], regions: [] };
    }
  }
}

