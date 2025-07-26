import "./bootstrap";
import "../css/app.css";
import "element-plus/dist/index.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import ElementPlus from "element-plus";
import { createPinia } from "pinia";

const pinia = createPinia();

createInertiaApp({
    title: (title) => `Cashira - Shun`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ElementPlus)
            .use(pinia)
            .mount(el);
    },
});
