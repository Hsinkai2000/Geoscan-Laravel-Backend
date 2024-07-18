import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "0.0.0.0",
        port:8000,
        hmr:{
            host: "0.0.0.0",
            port:8000,
        }
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/scss/login.scss",
                "resources/scss/home.scss",
                "resources/js/home.js",
                "resources/scss/base.scss",
                "resources/scss/project.scss",
                "resources/js/project.js",
                "resources/scss/measurement_point.scss",
                "resources/js/measurement_point.js",
                "resources/scss/pdf.scss",
                "resources/js/pdf.js",
            ],
            refresh: true,
        }),
    ],
});
