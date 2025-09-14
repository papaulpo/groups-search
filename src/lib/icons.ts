export function getNetworkIcon(type: string): string {
  const icons: Record<string, string> = {
    'Bluesky': '/global/img/bluesky.svg',
    'Facebook - Groupe': '/global/img/facebookg.svg',
    'Facebook - Page': '/global/img/facebookp.svg',
    'Instagram': '/global/img/instagram.svg',
    'Piaille': '/global/img/piaille.png',
    'Signal': '/global/img/signal.svg',
    'Site web': '/global/img/cursor-click.svg',
    'Telegram': '/global/img/telegram.svg',
    'TikTok': '/global/img/tiktok.svg',
    'X (Twitter)': '/global/img/x-logo.svg',
  };
  return icons[type] || '/global/img/site.svg';
}
