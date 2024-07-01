import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "0.0.0.0",
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
            ],
            refresh: true,
        }),
    ],
});
