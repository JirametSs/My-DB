import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // Specify the input files for CSS and JS, which are the entry points for Vite to bundle
            input: [
                'resources/css/app.css', // Main CSS file for the application
                'resources/js/app.js'   // Main JS file for the application
            ],
            // Enable automatic refresh on file changes for better development experience
            refresh: true,
        }),
    ],
    // Server configuration for local development
    server: {
        // The host that the server will bind to. Default is localhost, but it can be customized.
        host: '127.0.0.1', // You can change this to '0.0.0.0' if you want to access it from other devices on your network
        port: 3000, // The port on which the dev server will run. You can change this if another service is using this port.
        open: true, // Automatically opens the browser when the server starts
        hmr: {
            overlay: true, // Displays errors and warnings as overlays on the browser, useful for debugging
        },
        // Optional proxy configuration (if you have an API or backend running on a different port)
        // proxy: {
        //     '/api': {
        //         target: 'http://localhost:8000',
        //         changeOrigin: true,
        //         rewrite: (path) => path.replace(/^\/api/, ''),
        //     },
        // },
    },
    build: {
        // Configuration for the build output
        sourcemap: true, // Generate sourcemaps for easier debugging in development and production
        outDir: 'public/build', // Output directory for build files, you can change it if needed
        rollupOptions: {
            output: {
                // Create chunks manually for better caching and performance
                manualChunks: (id) => {
                    if (id.includes('node_modules')) {
                        return 'vendor'; // All dependencies from node_modules will be bundled in a separate file
                    }
                },
            },
        },
        // Other optional configurations you might want to include
        // minify: 'terser', // Use Terser for minification (default), or you can set it to 'esbuild' for faster builds
        // assetsInlineLimit: 4096, // Limit for inlining assets (in bytes). Increase if you want to inline larger files.
    },
    resolve: {
        // Define aliases to shorten and simplify import paths
        alias: {
            '@': '/resources/js', // Use '@' to reference the JS directory easily
            '~': '/resources/css', // Use '~' to reference the CSS directory easily
            // You can add more aliases for other common directories if needed
            // 'components': '/resources/js/components',
            // 'utils': '/resources/js/utils',
        },
    },
    css: {
        // Optional CSS configurations
        preprocessorOptions: {
            // Configure options for CSS preprocessors like Sass
            scss: {
                additionalData: `@import "@/styles/variables.scss";` // Automatically import variables into every SCSS file
            },
        },
    },
    optimizeDeps: {
        // Dependencies that need to be pre-bundled for faster development builds
        include: [
            'axios', // Common example, include libraries like axios that are frequently used
            'vue', // If using Vue.js, you might want to include it here
        ],
        exclude: [
            // You can exclude certain libraries if needed
        ],
    },
});