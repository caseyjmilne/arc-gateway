import { useState, useEffect } from '@wordpress/element';
import { HashRouter, Routes, Route } from 'react-router-dom';
import { __ } from '@wordpress/i18n';
import axios from 'axios';
import AppHeader from './components/app-header.js';
import Dashboard from './pages/dashboard.jsx';
import Collections from './pages/collections.jsx';

const App = () => {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/wp-json/arc-gateway/v1/admin-data', {
                headers: {
                    'X-WP-Nonce': window.arcGatewayAdmin?.nonce || ''
                }
            });
            setData(response.data);
            setError(null);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    if (loading) {
        return (
            <div className="flex items-center justify-center min-h-[400px] bg-zinc-950 text-gray-400">
                <div>{__('Loading...', 'arc-gateway')}</div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="bg-zinc-950 min-h-screen -ml-[22px] p-8">
                <div className="bg-red-900/20 border border-red-800 rounded-lg p-4">
                    <p className="text-red-400">{__('Error loading data:', 'arc-gateway')} {error}</p>
                </div>
            </div>
        );
    }

    return (
        <HashRouter>
            <div className="-ml-[22px] -mt-[10px] -mr-[20px] w-[calc(100%+40px)] bg-zinc-800 min-h-screen text-gray-400">
                <AppHeader />

                {/* Main Content */}
                <div className="px-8 py-6">
                    <Routes>
                        <Route path="/" element={<Dashboard data={data} />} />
                        <Route path="/collections" element={<Collections data={data} />} />
                    </Routes>
                </div>
            </div>
        </HashRouter>
    );
};

export default App;
