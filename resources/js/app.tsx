import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import '../css/app.css';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Roles from "./pages/Roles";
import RolesList from "./pages/RolesList";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.tsx`,
            import.meta.glob('./pages/**/*.tsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});

function App() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/roles" element={<Roles />} />
                <Route path="/roles-list" element={<RolesList />} />
            </Routes>
        </BrowserRouter>
    );
}

export default App;
