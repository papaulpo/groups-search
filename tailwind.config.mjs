/** @type {import('tailwindcss').Config} */
export default {
	content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
	theme: {
		extend: {
			colors: {
				// Theme colors from .theme/theme.css
				primary: {
					yellow: '#F2CE1A',
					'yellow-light': '#FFF476',
				},
				alert: {
					red: '#FF0000',
					'red-light': '#FFABAB',
				},
				neutral: {
					dark: '#1D1D1B',
					medium: '#5D5B54',
					light: '#C1C0BB',
					cream: '#F5F5F1',
				},
				// Semantic aliases
				'theme-primary': '#F2CE1A',
				'theme-secondary': '#1D1D1B',
				'theme-accent': '#FFF476',
				'theme-danger': '#FF0000',
				'theme-bg': '#F9F8E9',
				'theme-text': '#1D1D1B',
				'theme-text-secondary': '#5D5B54',
				'theme-border': '#C1C0BB',
			},
			fontFamily: {
				'theme': ['Inter', 'sans-serif'],
			},
		},
	},
	plugins: [],
}
