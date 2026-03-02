export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#0f172a', /* slate-900 */
                    light: '#334155'
                },
                accent: '#f59e0b' /* amber-500 - construction accent */
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                heading: ['Montserrat', 'Inter', 'sans-serif']
            }
        }
    },
    plugins: [],
}
